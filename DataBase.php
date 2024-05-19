<?php
require_once 'DataBaseConfig.php';

class DataBase {
    public $connect;

    public function __construct() {
        $config = new DataBaseConfig();
        $this->connect = new mysqli(
            $config->servername,
            $config->username,
            $config->password,
            $config->databasename
        );

        if ($this->connect->connect_error) {
            die('Connection failed: ' . $this->connect->connect_error);
        }
    }

    public function registerUser($username, $password) {
        $stmt = $this->connect->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("ss", $username, $hashedPassword);
        return $stmt->execute();
    }

    public function loginUser($username, $password) {
        $stmt = $this->connect->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $hashedPassword);
        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                return $id;
            }
        }
        return false;
    }
}
?>
