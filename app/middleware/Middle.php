<?php

namespace App\middleware;

require_once "../../vendor/autoload.php";
use App\jwt\Authentic;

class Middle
{
    public static function validacao($token)
    {
        $retorno = [];
        try {
            if(!isset($token['Authorization'])){
                http_response_code(401);
                $retorno['erro'] = "Token não informado";
                echo json_encode($retorno);
                exit;
            } else {
                $jwt = new Authentic();
                $token = explode(" ", $token['Authorization'])[1];
                $decode = $jwt->decodeToken($token);
                if(!$decode){
                    http_response_code(401);
                    $retorno['erro'] = "Token inválido";
                    echo json_encode($retorno);
                    exit;
                }
            }
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno['erro'] = $e->getMessage();
        }
    }
}
