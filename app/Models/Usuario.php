<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    protected $table = "tb_usuarios";

    protected $primaryKey = "iUsuario_id";

    protected $hidden = [
        'sUsuario_password',
    ];

    public function cadastraUsuario($request)
    {
        try {
            DB::beginTransaction();
            $usuario = new Usuario();
            $usuario->sUsuario_nome = $request["sUsuario_nome"];
            $usuario->sUsuario_cpf = $request["sUsuario_cpf"];
            $usuario->sUsuario_email = $request["sUsuario_email"];
            $usuario->sUsuario_password = bcrypt($request["sUsuario_password"]);
            $usuario->iTipo_usuario_id = $request["iTipo_usuario_id"];
            $usuario->save();

            $carteira = new Carteira();
            $novaCarteira = $carteira->cadastraCarteira($usuario);

            DB::commit();
            return ['usuario' => $usuario->toArray(), 'carteira' => $novaCarteira];
        } catch (Exception $erro) {
            DB::rollback();
            return $erro->getMessage();
        }
    }
}
