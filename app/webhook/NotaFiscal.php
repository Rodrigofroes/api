<?php
require_once "../../vendor/autoload.php";

use App\models\NotaFiscalModel;

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
        $data_atual = new DateTime(); // Certifique-se de definir a data atual se ainda não estiver definida
        $intervalo = $data_atual->diff($data_vencimento);

        $dias_restantes = $intervalo->days + 1;
        if ($data_vencimento < $data_atual) {
            $dias_restantes = -$intervalo->days; // Se a data de vencimento for passada, use o valor negativo
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
$ch = curl_init();

$text['text'] = $retorno;
$json = json_encode($text);

curl_setopt($ch, CURLOPT_URL, 'https://server-n8n-rodrigo.uwqcav.easypanel.host/webhook-test/0e1bc2f7-b2ef-4eba-803e-fe05667f906b');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
curl_close($ch);

echo json_encode($retorno);
