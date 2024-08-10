<?php
namespace App\models;
require_once "../../vendor/autoload.php";
use App\db\Conexao;


class UsuarioModel{
    private int $id;
    private string $nome;
    private string $email;
    private string $telefone;
    private string $usuario;
    private string $senha;
    private int $tipo;

    public function __construct($id, $nome, $email, $telefone, $usuario, $senha, $tipo){
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->tipo = $tipo;
    }

    public function verificar(){
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_usuario WHERE user_username = :usuario AND user_ativo = 1 LIMIT 1";
        $stmt = $conexao->select($sql, [':usuario' => $this->usuario]);
        
        if(count($stmt) == 0){
            return false;
        } else {
            if(password_verify($this->senha, $stmt[0]['user_senha'])){
                return $stmt[0];
            } else {
                return false;
            }
        }
    }

    public function listarUsuarios(){
        $conexao = new Conexao();
        $sql = "SELECT user_id ,user_username, user_senha, dados_nome, dados_email, dados_tel  FROM tb_usuario
                INNER JOIN tb_dados_usuario ON tb_usuario.dados_id = tb_dados_usuario.dados_id";
        $stmt = $conexao->select($sql);
        return $stmt;
    }

    public function cadastrarUsuario($id_dados){
        $conexao = new Conexao();
        $sql ="INSERT INTO tb_usuario(user_username, user_senha, dados_id, tipo_id)VALUES(:usuario, :senha, :dados, :tipo)";
        $stmt = $conexao->insert($sql, [':usuario' => $this->usuario, ':senha' => password_hash($this->senha, PASSWORD_DEFAULT), ':dados' => $id_dados ,':tipo' => $this->tipo]);
        return $stmt;
    }

    public function consultarPorId(){
        $conexao = new Conexao();
        $sql = "SELECT user_id ,user_username, user_senha, dados_nome, dados_email, dados_tel FROM tb_usuario 
                INNER JOIN tb_dados_usuario ON tb_usuario.dados_id = tb_dados_usuario.dados_id 
                WHERE user_id = :id LIMIT 1";
        $stmt = $conexao->select($sql, [':id' => $this->id]);
        return $stmt;
    }

    public function deletarUsuario(){
        $conexao = new Conexao();
        $sql = "UPDATE tb_usuario SET  user_ativo = 0 WHERE user_id = :id";
        $stmt = $conexao->delete($sql, [':id' => $this->id]);
        if($stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }

    public function cadastrarDadosUsuarios(){
        $conexao = new Conexao();
        $sql = "INSERT INTO tb_dados_usuario(dados_nome, dados_email, dados_tel)VALUES(:nome, :email, :telefone)";
        $stmt = $conexao->insert($sql, [':nome' => $this->nome, ':email' => $this->email, ':telefone' => $this->telefone]);
        return $stmt;
    }

    public function alterarUsuario(){
        $conexao = new Conexao();
        $sql = "UPDATE tb_usuario SET user_username = :usuario, user_senha = :senha, tipo_id = :tipo WHERE user_id = :id";
        $stmt = $conexao->update($sql, [':usuario' => $this->usuario, ':senha' => password_hash($this->senha, PASSWORD_DEFAULT), ':tipo' => $this->tipo, ':id' => $this->id]);
        if($stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }

    public function verificarPorNome(){
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_usuario WHERE user_username = :usuario AND user_id != :id";
        $stmt = $conexao->select($sql, [':usuario' => $this->usuario, ':id' => $this->id]);
        if(count($stmt) == 0){
            return false;
        } else {
            return true;
        }
    }
}


?>