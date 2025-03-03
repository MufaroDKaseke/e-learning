<?php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "nust_elearning";
    private static $conn = null;

    public function connect() {
        if (self::$conn === null) {
            self::$conn = mysqli_connect($this->host, $this->username, $this->password, $this->database);
            if (!self::$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        }
        return self::$conn;
    }

    public function query($sql) {
        $this->connect();
        $result = mysqli_query(self::$conn, $sql);
        if (!$result) {
            die("Query failed: " . mysqli_error(self::$conn));
        }
        return $result;
    }

    public function escape($value) {
        $this->connect();
        return mysqli_real_escape_string(self::$conn, $value);
    }

    public function close() {
        if (self::$conn !== null) {
            mysqli_close(self::$conn);
            self::$conn = null;
        }
    }
}
