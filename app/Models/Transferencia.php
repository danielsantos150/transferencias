<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transferencia extends Model
{
    use HasFactory;

    protected $table = 'tb_historico_transferencias';

    protected $primary_key = 'iHistorico_id';

    public function realizaTransferencia($request)
    {
        try {
            $transacao = false;
            $carteira = new Carteira();
            $oPagador = $carteira->getCarteira($request['iCarteira_id'], $carteira::PAGADOR);
            $oBeneficiario = $carteira->getCarteira($request['iUsuario_id'], $carteira::BENEFICIARIO);
            if ($this->verificaPermiteTransferir($oPagador, $oBeneficiario, $request) === true) {
                $transacao = $this->completaTransferencia($oPagador, $oBeneficiario, $request);
            }
            $oCarteiraAtualizado = $carteira->getCarteira($request['iCarteira_id'], $carteira::PAGADOR);
            $notificacao = $this->verificaServicoNotificador();
            if ($transacao === TRUE && $notificacao === TRUE) {
                $retorno = ['msg' => Helper::MESSAGE_SUCCESS, 'data' => $oCarteiraAtualizado];
            }else{
                if ($transacao === TRUE && $notificacao === FALSE) {
                    $retorno = ['msg' => Helper::MESSAGE_NOTIFY_ERROR, 'data' => $oCarteiraAtualizado];
                } else {
                    $retorno = ['msg' => 'Helper::MESSAGE_TRANSACTION', 'data' => $oCarteiraAtualizado];
                }
            }
            return $retorno;
        } catch (Exception $erro) {
            return ['msg' => 'Falha ao realizar a transferência.', 'data' => $erro->getMessage()];
        }
    }

    public function verificaPermiteTransferir($oPagador, $oBeneficiario, $request)
    {
        if (empty($oPagador) || empty($oBeneficiario)) {
            $retorno = ['msg' => 'Um dos usuários informados não possuem carteira.', 'data' => ''];
        } else {
            if ($oPagador->iTipo_usuario_id === TipoUsuario::LOGISTA) {
                $retorno = ['msg' => 'O usuário pagador é um lojista, ele apenas recebe transferências.', 'data' => $oPagador];
            } else {
                if (floatval($oPagador->fSaldo_carteira) < $request['fValor_transferido']) {
                    $retorno = ['msg' => 'Saldo insuficiente para realizar a transação.', 'data' => $oPagador];
                } else {
                    $retorno = true;
                }
            }
        }
        return $retorno;
    }

    public function completaTransferencia($oPagador, $oBeneficiario, $request)
    {
        try {
            DB::beginTransaction();
            if ($this->verificaServicoAutorizador()) {
                $carteiraPagador = Carteira::find($oPagador->iCarteira_id);
                $carteiraPagador->fSaldo_carteira -= $request['fValor_transferido'];
                $carteiraPagador->save();
                $carteiraBeneficiario = Carteira::find($oBeneficiario->iCarteira_id);
                $carteiraBeneficiario->fSaldo_carteira += $request['fValor_transferido'];
                $carteiraBeneficiario->save();
                DB::commit();
                return TRUE;
            }
            DB::rollback();
            return FALSE;
        } catch (Exception $erro) {
            return ['msg' => 'Falha ao realizar a transferência.', 'data' => $erro->getMessage()];
        }
    }

    public function verificaServicoAutorizador()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6', [
            'verify' => false
        ]);
        $status_code = $response->getStatusCode();
        $json_response = json_decode($response->getBody()->getContents());
        return $status_code === 200 && strtolower($json_response->message) === 'autorizado';
    }

    public function verificaServicoNotificador()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04', [
            'verify' => false
        ]);
        $status_code = $response->getStatusCode();
        $json_response = json_decode($response->getBody()->getContents());
        return $status_code === 200 && strtolower($json_response->message) === 'enviado';
    }
}
