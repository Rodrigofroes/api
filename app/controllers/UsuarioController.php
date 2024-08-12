<?php
require_once "../../vendor/autoload.php";

use App\models\UsuarioModel;
use App\middleware\Middle;

$json = file_get_contents('php://input');
$dados = json_decode($json);

$token = apache_request_headers();
Middle::validacao($token);

function listarUsuarios()
{
    $retorno = [];
    try {
        $model = new UsuarioModel(0, "", "", "", "", "", 0);
        $retorno = $model->listarUsuarios();
        if ($retorno) {
            http_response_code(200);
        } else {
            http_response_code(404);
            $retorno['erro'] = "Nenhum usuário encontrado";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function cadastrar($nome, $email, $telefone, $usuario, $senha, $tipo)
{
    $retorno = [];
    try {
        if (empty($usuario) && empty($senha)) {
            http_response_code(400);
            $retorno['erro'] = "Usuário e senha são obrigatórios";
        } else {
            $model = new UsuarioModel(0, $nome, $email, $telefone, $usuario, $senha, $tipo);
            $verificar = $model->verificarPorNome();

            if ($verificar) {
                http_response_code(400);
                $retorno['erro'] = "Usuário já cadastrado";
            } else {
                $id_usuario = $model->cadastrarDadosUsuarios();
                $cadastro = $model->cadastrarUsuario($id_usuario);
                http_response_code(201);
                $retorno['mensagem'] = "Usuário cadastrado com sucesso";
            }
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function consultarPorId($id)
{
    $retorno = [];
    try {
        $model = new UsuarioModel($id, "", "", "", "", "", 0);
        $retorno = $model->consultarPorId($id);
        if ($retorno) {
            http_response_code(200);
        } else {
            http_response_code(404);
            $retorno['erro'] = "Usuário não encontrado";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function deletarUsuario($id)
{
    $retorno = [];
    try {
        $model = new UsuarioModel($id, "", "", "", "", "", 0);
        $value = $model->deletarUsuario();
        if ($value) {
            http_response_code(201);
            $retorno['mensagem'] = "Usuário deletado com sucesso";
        } else {
            http_response_code(404);
            $retorno['erro'] = "Usuário não encontrado";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

function alterarUsuario($id, $nome, $email, $telefone, $usuario, $senha, $tipo)
{
    $retorno = [];
    try {
        $model = new UsuarioModel($id, $nome, $email, $telefone, $usuario, $senha, $tipo);
        $verificar = $model->verificarPorNome();

        if ($verificar) {
            http_response_code(400);
            $retorno['erro'] = "Usuário já cadastrado";
        } else {
            $cadastro = $model->alterarUsuario();
            http_response_code(201);
            $retorno['mensagem'] = "Usuário alterado com sucesso";
        }
    } catch (\Exception $e) {
        http_response_code(500);
        $retorno['erro'] = $e->getMessage();
    }
    echo json_encode($retorno);
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (isset($dados->usuario) && isset($dados->senha) && isset($dados->tipo)) {
            cadastrar($dados->nome, $dados->email, $dados->telefone, $dados->usuario, $dados->senha, $dados->tipo);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "Usuário, senha e tipo são obrigatórios"]);
        }
        break;
    case 'GET':
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            consultarPorId($id);
        } else {
            listarUsuarios();
        }
        break;
    case 'PUT':
        if (isset($dados->id) && isset($dados->nome) && isset($dados->email) && isset($dados->telefone) && isset($dados->usuario) && isset($dados->senha) && isset($dados->tipo)) {
            alterarUsuario($dados->id, $dados->nome, $dados->email, $dados->telefone, $dados->usuario, $dados->senha, $dados->tipo);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "Todos os campos são obrigatórios"]);
        }
        break;
    case 'DELETE':
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            deletarUsuario($id);
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
