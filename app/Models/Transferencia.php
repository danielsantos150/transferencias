<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;


class Transferencia extends Model
{
    use HasFactory;

    protected $table = "tb_historico_transferencias";

    protected $primary_key = "iHistorico_id";

    public function realizaTransferencia($request)
    {
        try {
            DB::beginTransaction();
            $carteira = new Carteira();
            $oPagador = $carteira->getCarteira($request['iCarteira_id'], $carteira::PAGADOR);
            $oBeneficiario = $carteira->getCarteira($request['iUsuario_id'], $carteira::BENEFICIARIO);
            if (empty($oPagador) || empty($oBeneficiario)) {
                return Helper::buildJson(Response::HTTP_PARTIAL_CONTENT, Helper::MESSAGE_FAIL, "Um dos usuários informados não possuem carteira.");
            }else if($oPagador->iTipo_usuario_id == TipoUsuario::LOGISTA){
                return Helper::buildJson(Response::HTTP_PRECONDITION_FAILED, Helper::MESSAGE_FAIL, "O usuário pagador é um lojista, ele apenas recebe transferências.");
            }else if(floatval($oPagador->fSaldo_carteira) < $request["fValor_transferido"]){
                return Helper::buildJson(Response::HTTP_PRECONDITION_FAILED, Helper::MESSAGE_FAIL, "Saldo insuficiente para realizar a transação.");
            }else{
                $this->completaTransferencia($oPagador, $oBeneficiario, $request);
            }
            DB::commit();
            $oCarteiraAtual = $carteira->getCarteira($request['iCarteira_id'], $carteira::PAGADOR);
            if($this->verificaServicoNotificador()){
                return Helper::buildJson(Response::HTTP_OK, Helper::MESSAGE_SUCCESS, $oCarteiraAtual);
            }else{
                return Helper::buildJson(Response::HTTP_OK, Helper::MESSAGE_NOTIFY_ERROR, $oCarteiraAtual);
            }
        } catch (Exception $erro) {
            DB::rollback();
            return Helper::buildJsonError($erro->getMessage());
        }
    }

    public function completaTransferencia($oPagador, $oBeneficiario, $request)
    {
        try {
            if($this->verificaServicoAutorizador()){
                $carteiraPagador = Carteira::find($oPagador->iCarteira_id);
                $carteiraPagador->fSaldo_carteira = $carteiraPagador->fSaldo_carteira - $request["fValor_transferido"];
                $carteiraPagador->save();
                $carteiraBeneficiario = Carteira::find($oBeneficiario->iCarteira_id);
                $carteiraBeneficiario->fSaldo_carteira += $request["fValor_transferido"];
                $carteiraBeneficiario->save();
            }
        } catch (Exception $erro) {
            return Helper::buildJsonError($erro->getMessage());
        }
    }

    public function verificaServicoAutorizador()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6', [
                'verify' => false
            ]);
            $status_code = $response->getStatusCode();
            $json_response = json_decode($response->getBody()->getContents());
            return ($status_code == 200 && strtolower($json_response->message) == "autorizado");
        } catch (Exception $erro) {
            return Helper::buildJsonError($erro->getMessage());
        }
    }

    public function verificaServicoNotificador()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04', [
                'verify' => false
            ]);
            $status_code = $response->getStatusCode();
            $json_response = json_decode($response->getBody()->getContents());
            return ($status_code == 200 && strtolower($json_response->message) == "enviado");
        } catch (Exception $erro) {
            return Helper::buildJsonError($erro->getMessage());
        }
    }

}
