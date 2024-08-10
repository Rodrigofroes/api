<?php
require_once "../../vendor/autoload.php";

use App\middleware\Middle;
use App\models\NotaFiscalModel;

$json = file_get_contents('php://input');
$dados = json_decode($json);

$token = apache_request_headers();
Middle::validacao($token);

function listarNotasFiscais()
{
    $retorno = [];
    try {
        $model = new NotaFiscalModel(0, "", 0, "", "", "", "", 0, 0);
        $retorno = $model->listarNotasFiscais();
        if ($retorno) {
            http_response_code(200);
        } else {
            http_response_code(404);
            $retorno['erro'] = "Nenhuma nota fiscal encontrada";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function cadastrarNotasFiscais($numero, $valor_total, $data_emissao, $data_vencimento, $data_entrada, $observacao, $usuario_id, $fornecedor_id)
{
    $retorno = [];
    try {
        if (empty($numero) && empty($valor_total) && empty($data_emissao) && empty($data_vencimento) && empty($data_entrada) && empty($observacao) && empty($usuario_id) && empty($fornecedor_id)) {
            http_response_code(400);
            $retorno['erro'] = "Todos os campos são obrigatórios";
        } else {
            $model = new NotaFiscalModel(0, $numero, $valor_total, $data_emissao, $data_vencimento, $data_entrada, $observacao, $usuario_id, $fornecedor_id);
            $verifical = $model->verificarNotaFiscal();
            if($verifical){
                http_response_code(400);
                $retorno['erro'] = "Nota fiscal já cadastrada";
            }else{
                $cadastro = $model->cadastrarNotasFiscais();
                http_response_code(201);
                $retorno['mensagem'] = "Nota fiscal cadastrada com sucesso";
            }
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function consultaNotaFiscal($id){
    $retorno = [];
    try {
        $model = new NotaFiscalModel($id, "", 0, "", "", "", "", 0, 0);
        $retorno = $model->consultaNotaFiscal();
        if ($retorno) {
            http_response_code(200);
        } else {
            http_response_code(404);
            $retorno['erro'] = "Nenhuma nota fiscal encontrada";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function excluirNotaFiscal($id){
    $retorno = [];
    try {
        $model = new NotaFiscalModel($id, "", 0, "", "", "", "", 0, 0);
        $value = $model->excluirNotaFiscal();
        if ($value) {
            http_response_code(200);
            $retorno['mensagem'] = "Nota fiscal excluída com sucesso";
        } else {
            http_response_code(404);
            $retorno['erro'] = "Nenhuma nota fiscal encontrada";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            consultaNotaFiscal($_GET['id']);
        } else {
            listarNotasFiscais();
        }
        break;
    case 'POST':
        if (isset($dados->numero) && isset($dados->valor_total) && isset($dados->data_emissao) && isset($dados->data_vencimento) && isset($dados->data_entrada) && isset($dados->observacao) && isset($dados->usuario_id) && isset($dados->fornecedor_id)) {
            cadastrarNotasFiscais($dados->numero, $dados->valor_total, $dados->data_emissao, $dados->data_vencimento, $dados->data_entrada, $dados->observacao, $dados->usuario_id, $dados->fornecedor_id);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "Todos os campos são obrigatórios"]);
        }
        break;
    case 'DELETE':
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            excluirNotaFiscal($_GET['id']);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "ID é obrigatório"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido"]);
        break;
}
