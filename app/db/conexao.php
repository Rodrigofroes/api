<?php

namespace App\db;

require_once "../../vendor/autoload.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

use PDO;

class Conexao
{
    private $instance;
    public function __construct()
    {
        $this->instance = new PDO(
            'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8',
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
        $this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function __destruct()
    {
        $this->instance = null;
    }

    public function select($sql, $params = [])
    {
        $stmt = $this->instance->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($sql, $params = [])
    {
        $stmt = $this->instance->prepare($sql);
        $stmt->execute($params);
        return $this->instance->lastInsertId();
    }

    public function delete($sql, $params = [])
    {
        $stmt = $this->instance->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function update($sql, $params = [])
    {
        $stmt = $this->instance->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
