<?php
namespace app\models;
use App\db\Conexao;

class EntradaModel{
    private int $id;
    private string $quantidade;
    private int $usuario_id;
    private int $produto_id;
    
    public function __construct($id, $quantidade, $usuario_id, $produto_id)
    {
        $this->id = $id;
        $this->quantidade = $quantidade;
        $this->usuario_id = $usuario_id;
        $this->produto_id = $produto_id;
    }


    public function cadastrarEntrada(){
        $conexao = new Conexao();
        $sql = "INSERT INTO tb_entrada(entrada_quantidade, entrada_data, pro_id, user_id)VALUES(:quantidade, NOW(), :id, :user)";
        $stmt = $conexao->insert($sql, [':quantidade' => $this->quantidade, ':id' => $this->produto_id, ':user' => $this->usuario_id]);
    }

}
?>