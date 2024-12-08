<?php

namespace App;

use PDO;
use PDOException;

class DBConnection
{
    private static $instance = null;
    private $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';
        try {
            $dsn = 'mysql:host=' . $config['host'] . ';dbname=' .  $config['dbname'];
            $username = $config['username'];
            $password = $config['password'];
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    public function __clone() {}

    public function __wakeup() {}

    public function getPDO()
    {
        return $this->pdo;
    }
}
