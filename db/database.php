<?php

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

require_once __DIR__ . '/../bootstrap.php';

class database {

    protected $conn;

    public function __construct() {
        try {
            $this->conn = appDatabaseConnection();
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