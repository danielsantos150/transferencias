<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Helper;
use Symfony\Component\HttpFoundation\Response;

class Carteira extends Model
{

    protected $table = "tb_carteira";

    protected $primaryKey = "iCarteira_id";

    public function cadastraCarteira($novoUsuario)
    {
        try {
            $carteira = new Carteira();
            $carteira->fSaldo_carteira = 0.00;
            $carteira->fSaldo_bloqueado = 0.00;
            $carteira->iUsuario_id = $novoUsuario->id;
            $carteira->save();
            return $carteira->toArray();
        } catch (Exception $erro) {
            throw new Exception($erro->getMessage(), 500);
        }
    }

    public function depositaSaldo($requestSaldo, $iCarteira_id)
    {
        try {
            $carteira = $this::find($iCarteira_id);
            $carteira->fSaldo_carteira += $requestSaldo["fSaldo_carteira"];
            $carteira->save();
            return Helper::buildJson(Response::HTTP_OK, Helper::MESSAGE_OK, $carteira->toArray());
        } catch (Exception $erro) {
            return Helper::buildJson(Response::HTTP_INTERNAL_SERVER_ERROR, Helper::MESSAGE_ERROR, ["error" => $erro->getMessage()]);
        }
    }
}
