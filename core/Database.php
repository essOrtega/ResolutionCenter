<?php

class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = new mysqli("localhost", "root", "", "resolution_center");

            if (self::$conn->connect_error) {
                die("Database connection failed: " . self::$conn->connect_error);
            }
        }

        return self::$conn;
    }
}
