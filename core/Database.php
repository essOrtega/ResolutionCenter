<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $name = "resolution_center";

    protected $conn;

    public function connect() {
        if (!$this->conn) {
            $this->conn = new mysqli(
                $this->host,
                $this->user,
                $this->pass,
                $this->name
            );

            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
