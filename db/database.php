<?php

class database {

    private $db_server = "localhost";
    private $db_username = "root";
    private $db_password = "";
    private $db_name = "vulneratrack_dbsuper";
    protected $conn;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->db_server};dbname={$this->db_name};charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->db_username, $this->db_password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    protected function getConnection() {
    if ($this->conn === null) {
        $this->__construct(); 
    }
    return $this->conn;
}
}