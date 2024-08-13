<?php
require_once "../../vendor/autoload.php";

use App\jwt\Authentic;
use App\models\EntradaModel;
use App\middleware\Middle;

$json = file_get_contents('php://input');
$dados = json_decode($json);

$token = apache_request_headers();
Middle::validacao($token);

function cadastrar($quantidade, $produto_id, $token)
{

    $retorno = [];
    try {
        if (empty($quantidade) || empty($produto_id)) {
            http_response_code(400);
            $retorno['erro'] = "Todos os campos são obrigatórios";
        } else {
            $newToken = explode(" ", $token['Authorization'])[1];
            $decode = Authentic::decodeToken($newToken);
            $usuario_id = $decode->id;

            $model = new EntradaModel(0, $quantidade, $usuario_id, $produto_id);
            $cadastro = $model->cadastrarEntrada();
            http_response_code(201);
            $retorno['mensagem'] = "Entrada cadastrada com sucesso";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($dados->quantidade) && isset($dados->produto_id)) {
            cadastrar($dados->quantidade, $dados->produto_id, $token);
        } else {
            http_response_code(400);
            $retorno['erro'] = "Todos os campos são obrigatórios";
            echo json_encode($retorno);
        }
        break;
    default:
        http_response_code(405);
        $retorno['erro'] = "Método não encontrado";
        echo json_encode($retorno);
        break;
}
