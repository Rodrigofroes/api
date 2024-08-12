<?php

namespace App\models;

require_once "../../vendor/autoload.php";

use App\db\Conexao;

class ProdutoModel
{
    private int $id;
    private string $codigo;
    private string $nome;
    private float $valor_venda;
    private float $valor_unitario;
    private int $categoria_id;
    private int $med_id;
    private int $quantidade_minima;
    private string $descricao;

    public function __construct($id, $codigo, $nome, $valor_venda, $valor_unitario, $categoria_id, $med_id, $quantidade_minima, $descricao)
    {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->valor_venda = $valor_venda;
        $this->valor_unitario = $valor_unitario;
        $this->categoria_id = $categoria_id;
        $this->med_id = $med_id;
        $this->quantidade_minima = $quantidade_minima;
        $this->descricao = $descricao;
    }

    public function listarProdutos()
    {
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_produto WHERE pro_ativo = 1";
        $stmt = $conexao->select($sql);
        return $stmt;
    }

    public function cadastrarProduto()
    {
        $conexao = new Conexao();
        $sql = "INSERT INTO tb_produto(pro_codigo, pro_nome, pro_valor_venda, pro_valor_unitario, cat_id, med_id, pro_estoque_minimo, pro_descricao) VALUES(:codigo, :nome, :valor_venda, :valor_unitario, :categoria, :medida, :quantidade_minima, :descricao)";
        $stmt = $conexao->insert($sql, [':codigo' => $this->codigo, ':nome' => $this->nome, ':valor_venda' => $this->valor_venda, ':valor_unitario' => $this->valor_unitario, ':categoria' => $this->categoria_id, ':medida' => $this->med_id, ':quantidade_minima' => $this->quantidade_minima, ':descricao' => $this->descricao]);
        return $stmt;
    }

    public function consultarPorId()
    {
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_produto WHERE pro_id = :id AND pro_ativo = 1";
        $stmt = $conexao->select($sql, [':id' => $this->id]);
        return $stmt;
    }

    public function deletarProduto()
    {
        $conexao = new Conexao();
        $sql = "UPDATE  tb_produto SET pro_ativo = 0 WHERE pro_id = :id";
        $stmt = $conexao->delete($sql, [':id' => $this->id]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function verificarProduto()
    {
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_produto WHERE pro_codigo = :codigo";
        $stmt = $conexao->select($sql, [':codigo' => $this->codigo]);
        if ($this->codigo == $stmt[0]['pro_codigo']) {
            return true;
        } else {
            return false;
        }
    }

    public function alterarProduto()
    {
        $conexao = new Conexao();
        $sql = "UPDATE tb_produto SET pro_codigo = :codigo, pro_nome = :nome, pro_valor_venda = :valor_venda, pro_valor_unitario = :valor_unitario, cat_id = :categoria, med_id = :medida, pro_estoque_minimo = :quantidade_minima, pro_descricao = :descricao WHERE pro_id = :id";
        $stmt = $conexao->update($sql, [':codigo' => $this->codigo, ':nome' => $this->nome, ':valor_venda' => $this->valor_venda, ':valor_unitario' => $this->valor_unitario, ':categoria' => $this->categoria_id, ':medida' => $this->med_id, ':quantidade_minima' => $this->quantidade_minima, ':descricao' => $this->descricao, ':id' => $this->id]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
