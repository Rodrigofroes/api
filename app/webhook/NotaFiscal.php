<?php
require_once "../../vendor/autoload.php";

use App\models\NotaFiscalModel;

date_default_timezone_set('America/Sao_Paulo');

function webNota()
{
    $model = new NotaFiscalModel(0, "", 0, "", "", "", "", 0, 0);
    $mes = date('m');

    $retorno = $model->webNotaFiscal($mes);

    $data_atual = new DateTime(); // Data atual
    $dias_alerta = 7; // Número de dias para o alerta

    $message = [];
    foreach ($retorno as $key => $value) {
        $data_vencimento = new DateTime($value['nota_data_vencimento']); // Data de vencimento da nota
        $intervalo = $data_atual->diff($data_vencimento); // Intervalo entre as datas

        $dias_restantes = $intervalo->days;

        if ($data_vencimento < $data_atual) {
            $message['message'] = "Olá, você tem uma nota fiscal vencida. Acesse o sistema para mais informações.";
        }
        $numero = $value['nota_num'];
        if ($dias_restantes <= $dias_alerta) {
            $msg = "Olá, você tem uma nota fiscal $numero com  vencimento em $dias_restantes dias. Acesse o sistema para mais informações.";
            $message[$key]['message'] = $msg;
        }
    }

    return $message;
}

$retorno = webNota();
?>


