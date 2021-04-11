<?php

namespace App\Models;

use Exception;
use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
            return Helper::buildJson(Response::HTTP_OK, Helper::MESSAGE_SUCCESS, $oCarteiraAtual);
        } catch (Exception $erro) {
            DB::rollback();
            return $erro->getMessage();
        }

    }

    public function completaTransferencia($oPagador, $oBeneficiario, $request)
    {
        try {
            $carteiraPagador = Carteira::find($oPagador->iCarteira_id);
            $carteiraPagador->fSaldo_carteira = $carteiraPagador->fSaldo_carteira - $request["fValor_transferido"];
            $carteiraPagador->save();
            $carteiraBeneficiario = Carteira::find($oBeneficiario->iCarteira_id);
            $carteiraBeneficiario->fSaldo_carteira += $request["fValor_transferido"];
            $carteiraBeneficiario->save();
        } catch (Exception $erro) {
            throw new Exception($erro->getMessage(), 1);
        }
    }

}
