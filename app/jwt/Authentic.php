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
        try {
            $token = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');
            return $token;
        } catch (\Exception $e) {
            return false;
        }
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
