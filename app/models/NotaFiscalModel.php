<?php
namespace App\models;
require_once "../../vendor/autoload.php";
use App\db\Conexao;

class NotaFiscalModel{
    private int $id;
    private string $numero;
    private float $valor_total;
    private string $data_emissao;
    private string $data_vencimento;
    private string $data_entrada;
    private string $observacao;
    private int $usuario_id;
    private int $fornecedor_id;

    public function __construct($id, $numero, $valor_total, $data_emissao, $data_vencimento, $data_entrada, $observacao, $usuario_id, $fornecedor_id)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->valor_total = $valor_total;
        $this->data_emissao = $data_emissao;
        $this->data_vencimento = $data_vencimento;
        $this->data_entrada = $data_entrada;
        $this->observacao = $observacao;
        $this->usuario_id = $usuario_id;
        $this->fornecedor_id = $fornecedor_id;
    }

    public function listarNotasFiscais()
    {
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_nota_fiscal WHERE nota_ativa = 1";
        $stmt = $conexao->select($sql);
        return $stmt;
    }

    public function webNotaFiscal($mes)
    {
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_nota_fiscal WHERE nota_ativa = 1 AND MONTH(nota_data_vencimento) = :mes";
        $stmt = $conexao->select($sql, [':mes' => $mes]);
        return $stmt;
    }

    public function cadastrarNotasFiscais(){
        $conexao = new Conexao();
        $sql = "INSERT INTO tb_nota_fiscal(nota_num, nota_valor, nota_data_emissao, nota_data_vencimento, nota_data_entrada, nota_observacao, user_id, for_id) VALUES(:numero, :valor_total, :data_emissao, :data_vencimento, :data_entrada, :observacao, :usuario_id, :fornecedor_id)";
        $stmt = $conexao->insert($sql, [':numero' => $this->numero, ':valor_total' => $this->valor_total, ':data_emissao' => $this->data_emissao, ':data_vencimento' => $this->data_vencimento, ':data_entrada' => $this->data_entrada, ':observacao' => $this->observacao, ':usuario_id' => $this->usuario_id, ':fornecedor_id' => $this->fornecedor_id]);
        return $stmt;
    }

    public function verificarNotaFiscal(){
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_nota_fiscal WHERE nota_num = :numero";
        $stmt = $conexao->select($sql, [':numero' => $this->numero]);
        return $stmt;
    }

    public function consultaNotaFiscal(){
        $conexao = new Conexao();
        $sql = "SELECT * FROM tb_nota_fiscal WHERE nota_id = :id";
        $stmt = $conexao->select($sql, [':id' => $this->id]);
        return $stmt;
    }

    public function excluirNotaFiscal(){
        $conexao = new Conexao();
        $sql = "UPDATE tb_nota_fiscal SET nota_ativa = 0 WHERE nota_id = :id";
        $stmt = $conexao->update($sql, [':id' => $this->id]);
        return $stmt;
    }

}
?>