<?php
require_once "../../vendor/autoload.php";

use App\models\ProdutoModel;
use App\middleware\Middle;

$json = file_get_contents('php://input');
$dados = json_decode($json);

$token = apache_request_headers(); 
Middle::validacao($token);

function cadastrarProduto($codigo, $nome, $valor_unitario, $valor_venda, $categoria, $tamanho, $quantidade_minima, $descricao)
{
    $retorno = [];
    try {
        if (empty($codigo) && empty($nome) && empty($valor_unitario) && empty($valor_venda) && empty($categoria) && empty($tamanho) && empty($quantidade_minina) && empty($descricao)) {
            http_response_code(400);
            $retorno['erro'] = "Todos os campos são obrigatórios";
        } else {
            $model = new ProdutoModel(0, $codigo, $nome, $valor_unitario, $valor_venda, $categoria, $tamanho, $quantidade_minima, $descricao);
            $verificar = $model->verificarProduto();
            if ($verificar) {
                http_response_code(400);
                $retorno['erro'] = "Produto já cadastrado";
            } else {
                $cadastro = $model->cadastrarProduto();
                http_response_code(201);
                $retorno['mensagem'] = "Produto cadastrado com sucesso";
            }
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function listarProdutos()
{
    $retorno = [];
    try {
        $model = new ProdutoModel(0, "", "", 0, 0, 0, 0, 0, "");
        $value = $model->listarProdutos();
        if ($value) {
            http_response_code(200);
            $retorno = $value;
        } else {
            http_response_code(404);
            $retorno['erro'] = "Nenhum produto encontrado";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function listarProdutoPorID($id)
{
    $retorno = [];
    try {
        $model = new ProdutoModel($id, "", "", 0, 0, 0, 0, 0, "");
        $value = $model->consultarPorId();
        if ($value) {
            http_response_code(200);
            $retorno = $value;
        } else {
            http_response_code(404);
            $retorno['erro'] = "Produto não encontrado";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function deletarProduto($id)
{
    $retorno = [];
    try {
        $model = new ProdutoModel($id, "", "", 0, 0, 0, 0, 0, "");
        $value = $model->deletarProduto();
        if ($value) {
            http_response_code(200);
            $retorno['mensagem'] = "Produto deletado com sucesso";
        } else {
            http_response_code(404);
            $retorno['erro'] = "Produto não encontrado";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function alterarProduto($id, $codigo, $nome, $valor_unitario, $valor_venda, $categoria, $tamanho, $quantidade_minima, $descricao)
{
    $retorno = [];
    try {
        if (empty($codigo) && empty($nome) && empty($valor_unitario) && empty($valor_venda) && empty($categoria) && empty($tamanho) && empty($quantidade_minina) && empty($descricao)) {
            http_response_code(400);
            $retorno['erro'] = "Todos os campos são obrigatórios";
        } else {
            $model = new ProdutoModel($id, $codigo, $nome, $valor_unitario, $valor_venda, $categoria, $tamanho, $quantidade_minima, $descricao);
            $verificar = $model->verificarProduto();
            if ($verificar) {
                http_response_code(400);
                $retorno['erro'] = "Produto já cadastrado";
            } else {
                $cadastro = $model->alterarProduto();
                http_response_code(201);
                $retorno['mensagem'] = "Produto alterado com sucesso";
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
        if (isset($dados->codigo) && isset($dados->nome) && isset($dados->valor_unitario) && isset($dados->valor_venda) && isset($dados->categoria) && isset($dados->tamanho) && isset($dados->quantidade_minima) && isset($dados->descricao)) {
            cadastrarProduto($dados->codigo, $dados->nome, $dados->valor_unitario, $dados->valor_venda, $dados->categoria, $dados->tamanho, $dados->quantidade_minima, $dados->descricao);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "Todos os campos são obrigatórios"]);
        }
        break;
    case 'GET':
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            listarProdutoPorID($_GET['id']);
        } else {
            listarProdutos();
        }
        break;
    case 'DELETE':
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            deletarProduto($_GET['id']);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "ID é obrigatório"]);
        }
        break;
    case 'PUT':
        if (isset($dados->id) && isset($dados->codigo) && isset($dados->nome) && isset($dados->valor_unitario) && isset($dados->valor_venda) && isset($dados->categoria) && isset($dados->tamanho) && isset($dados->quantidade_minima) && isset($dados->descricao)) {
            alterarProduto($dados->id, $dados->codigo, $dados->nome, $dados->valor_unitario, $dados->valor_venda, $dados->categoria, $dados->tamanho, $dados->quantidade_minima, $dados->descricao);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "Todos os campos são obrigatórios"]);
        }
        break;
    default:
        echo json_encode(["erro" => "Método não permitido"]);
        break;
}
