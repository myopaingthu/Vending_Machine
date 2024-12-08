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
        try {
            $dsn = 'mysql:host=localhost;dbname=vending_machine';
            $username = 'root';
            $password = '';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            // Create a PDO instance
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    // Prevent cloning of the instance
    public function __clone() {}

    // Prevent unserializing the instance
    public function __wakeup() {}

    // Get the singleton instance
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // Get the PDO instance
    public function getPDO()
    {
        return $this->pdo;
    }
}
