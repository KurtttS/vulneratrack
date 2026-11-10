<?php

class database {

    protected $conn;

    public function __construct() {
        $host = getenv('SUPABASE_DB_HOST');
        $port = getenv('SUPABASE_DB_PORT') ?: '5432';
        $database = getenv('SUPABASE_DB_NAME');
        $username = getenv('SUPABASE_DB_USER');
        $password = getenv('SUPABASE_DB_PASSWORD');

        if (!$host || !$database || !$username || $password === false) {
            throw new RuntimeException(
                'Set SUPABASE_DB_HOST, SUPABASE_DB_NAME, SUPABASE_DB_USER, and SUPABASE_DB_PASSWORD.'
            );
        }

        try {
            $dsn = "pgsql:host={$host};port={$port};dbname={$database};sslmode=require";
            $this->conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Supabase PostgreSQL connection failed.', 0, $e);
        }
    }

    protected function getConnection() {
        if ($this->conn === null) {
            $this->__construct();
        }
        return $this->conn;
    }
}