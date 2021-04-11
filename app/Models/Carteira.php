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

    public const PAGADOR = 1;
    public const BENEFICIARIO = 2;

    public function cadastraCarteira($novoUsuario)
    {
        try {
            $carteira = new Carteira();
            $carteira->fSaldo_carteira = 0.00;
            $carteira->fSaldo_bloqueado = 0.00;
            $carteira->iUsuario_id = $novoUsuario->iUsuario_id;
            $carteira->save();
            return $carteira->toArray();
        } catch (Exception $erro) {
            return Helper::buildJsonError($erro->getMessage());
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
            return Helper::buildJsonError($erro->getMessage());
        }
    }

    public function getCarteira($id, $tp_usuario)
    {
        try {
            $carteira = DB::table('tb_usuarios as u')
                ->join('tb_tipo_usuario as tp', 'tp.iTipo_usuario_id', '=', 'u.iTipo_usuario_id')
                ->join('tb_carteira as c', 'c.iUsuario_id', '=', 'u.iUsuario_id')
                ->select('u.iUsuario_id', 'u.sUsuario_nome', 'u.sUsuario_cpf',
                 'tp.iTipo_usuario_id', 'sTipo_usuario',
                 'c.iCarteira_id', 'c.fSaldo_carteira');
            if($tp_usuario == self::PAGADOR){
                $carteira->where('c.iCarteira_id', '=', $id);
            }else if($tp_usuario == self::BENEFICIARIO){
                $carteira->where('c.iUsuario_id', '=', $id);
            }
            return $carteira->get()->first();
        } catch (Exception $erro) {
            return Helper::buildJsonError($erro->getMessage());
        }
    }
}
