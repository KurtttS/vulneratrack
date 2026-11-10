<?php

require_once __DIR__ . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

function appEnvironment(string $name, ?string $default = null): ?string
{
    $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);
    return $value === false || $value === null ? $default : $value;
}

function appCreateDatabaseConnection(): PDO
{
    $host = appEnvironment('SUPABASE_DB_HOST');
    $port = appEnvironment('SUPABASE_DB_PORT', '5432');
    $database = appEnvironment('SUPABASE_DB_NAME');
    $username = appEnvironment('SUPABASE_DB_USER');
    $password = appEnvironment('SUPABASE_DB_PASSWORD');

    if (!$host || !$database || !$username || $password === null || $password === '') {
        throw new RuntimeException(
            'Set SUPABASE_DB_HOST, SUPABASE_DB_NAME, SUPABASE_DB_USER, and SUPABASE_DB_PASSWORD.'
        );
    }

    $dsn = "pgsql:host={$host};port={$port};dbname={$database};sslmode=require";
    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

function appDatabaseConnection(): PDO
{
    static $connection;
    if (!$connection) {
        try {
            $connection = appCreateDatabaseConnection();
        } catch (PDOException $e) {
            throw new RuntimeException('Supabase PostgreSQL connection failed.', 0, $e);
        }
    }
    return $connection;
}

final class PostgresSessionHandler implements SessionHandlerInterface
{
    private ?PDO $connection = null;
    private bool $transactionActive = false;

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        if ($this->transactionActive) {
            $this->connection->commit();
            $this->transactionActive = false;
        }
        $this->connection = null;
        return true;
    }

    public function read(string $id): string|false
    {
        $this->connection = appCreateDatabaseConnection();
        $this->connection->beginTransaction();
        $this->transactionActive = true;

        $insert = $this->connection->prepare(
            'INSERT INTO app_private.php_sessions (session_id, session_data, last_activity)
             VALUES (?, \'\', ?)
             ON CONFLICT (session_id) DO NOTHING'
        );
        $insert->execute([$id, time()]);

        $select = $this->connection->prepare(
            'SELECT session_data
             FROM app_private.php_sessions
             WHERE session_id = ?
             FOR UPDATE'
        );
        $select->execute([$id]);
        $data = $select->fetchColumn();
        if ($data === false) {
            return '';
        }

        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            throw new RuntimeException('Stored PHP session data is invalid.');
        }
        return $decoded;
    }

    public function write(string $id, string $data): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO app_private.php_sessions (session_id, session_data, last_activity)
             VALUES (?, ?, ?)
             ON CONFLICT (session_id) DO UPDATE
             SET session_data = EXCLUDED.session_data,
                 last_activity = EXCLUDED.last_activity'
        );
        return $statement->execute([$id, base64_encode($data), time()]);
    }

    public function destroy(string $id): bool
    {
        $statement = $this->connection->prepare(
            'DELETE FROM app_private.php_sessions WHERE session_id = ?'
        );
        return $statement->execute([$id]);
    }

    public function gc(int $maxLifetime): int|false
    {
        $statement = $this->connection->prepare(
            'DELETE FROM app_private.php_sessions WHERE last_activity < ?'
        );
        $statement->execute([time() - $maxLifetime]);
        return $statement->rowCount();
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_set_save_handler(new PostgresSessionHandler(), true);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set(
        'session.cookie_secure',
        appEnvironment('APP_ENV') === 'production' || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? '1' : '0'
    );
    ini_set('session.cookie_samesite', 'Lax');
}
