<?php
require_once "../../vendor/autoload.php";

use App\models\UsuarioModel;
use App\jwt\Authentic;

$json = file_get_contents('php://input');
$dados = json_decode($json);


function login($usuario, $senha)
{
    $retorno = [];
    try {
        if (empty($usuario) || empty($senha)) {
            http_response_code(400);
            $retorno['erro'] = "Usuário e senha são obrigatórios";
        } else {
            $model = new UsuarioModel(0,"", "", "", $usuario, $senha, 0);
            $verificar = $model->verificar();

            if ($verificar) {
                $payload = array(
                    "id" => $verificar['user_id'],
                    "usuario" => $verificar['user_username'],
                );
                $jwt = new Authentic();
                $jwt = $jwt->gerarToken($payload);
                http_response_code(201);
                $retorno['token'] = $jwt;
            } else {
                http_response_code(401);
                $retorno['erro'] = "Usuário ou senha inválidos";
            }
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}


switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($dados->usuario) && isset($dados->senha)) {
            login($dados->usuario, $dados->senha);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "Usuário e senha são obrigatórios"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido"]);
        break;
}
