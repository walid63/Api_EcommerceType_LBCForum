<?php 

require_once "./server/config/config.php";

class _connect
{
    use DatabaseConfig;
    private static $instance;
    private $connection;

    private function __construct()
    {
        // Récupérer les informations de connexion à partir de config.php
        $dsn = "mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName . ";charset=utf8mb4";

        try {
            $this->connection = new \PDO($dsn, $this->dbUser, $this->dbPass);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getTables()
    {
        $query = "SHOW TABLES";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        $tables = $statement->fetchAll(PDO::FETCH_COLUMN);

        return $tables;
    }
}