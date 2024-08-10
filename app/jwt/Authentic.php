<?php
namespace App\jwt;
require_once "../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Authentic
{
    public static function gerarToken($payload)
    {
        $key = $_ENV['SECRET_KEY'];
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    public static function decodeToken($token)
    {
        try {
            $decoded = JWT::decode($token, new key($_ENV['SECRET_KEY'], 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}