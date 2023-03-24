<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php-sql_poste_Vincent_v2/.config/db_config.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . '/sirjacques.vincent/php-sql_poste_Vincent_v2/.config/db_config.php';
class Database
{
    private $pdo;
    public function __construct()
    {
        $this->connect();
    }
    public function __destruct()
    {
    }
    private function connect()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";charset=utf8", DB_USER, DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de donnée: " . $e->getMessage());
        }
    }
    public function getPDO()
    {
        return $this->pdo;
    }
}
