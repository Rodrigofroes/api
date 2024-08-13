<?php
require_once "../../vendor/autoload.php";

use App\models\NotaFiscalModel;
use App\webhook\Servicos;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

date_default_timezone_set('America/Sao_Paulo');

function webNota()
{
    $model = new NotaFiscalModel(0, "", 0, "", "", "", "", 0, 0);
    $mes = date('m');

    $retorno = $model->webNotaFiscal($mes);

    $data_atual = new DateTime();
    $dias_alerta = 7;

    $message = [];
    foreach ($retorno as $key => $value) {
        $data_vencimento = new DateTime($value['nota_data_vencimento']);
        $data_atual = new DateTime();
        $intervalo = $data_atual->diff($data_vencimento);

        $dias_restantes = $intervalo->days + 1;
        if ($data_vencimento < $data_atual) {
            $dias_restantes = -$intervalo->days;
        }

        $numero = $value['nota_num'];
        if ($dias_restantes <= $dias_alerta) {
            if ($dias_restantes == 0) {
                $message[$key]['message'] = "A nota fiscal de número $numero vence hoje";
            } elseif ($dias_restantes < 0) {
                $message[$key]['message'] = "A nota fiscal de número $numero estava vencida há " . abs($dias_restantes) . " dias";
            } else {
                $message[$key]['message'] = "A nota fiscal de número $numero vence em $dias_restantes dias";
            }
        }
    }


    return $message;
}

$retorno = webNota();
$text['text'] = $retorno;
$json = json_encode($text);
$model = new Servicos($_ENV['N8N_URL'], "POST", $json);
$message = $model->sendMessage();
