<?php

namespace App\Models;

use Exception;
use Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = "tb_usuarios";

    protected $primaryKey = "iUsuario_id";

    protected $fillable = [
        'sUsuario_nome',
        'sUsuario_cpf',
        'password',
        'sUsuario_email',
        'iTipo_usuario_id',
    ];

    /*protected $hidden = [
        'password',
    ];*/

    public function cadastraUsuario($request)
    {
        try {
            DB::beginTransaction();
            $usuario = User::create([
                'sUsuario_nome' => $request["sUsuario_nome"],
                'sUsuario_cpf' => $request["sUsuario_cpf"],
                'sUsuario_email' => $request["sUsuario_email"],
                'password' => bcrypt($request["sUsuario_password"]),
                'iTipo_usuario_id' => $request["iTipo_usuario_id"]
            ]);
            $token = $usuario->createToken('API Token')->plainTextToken;
            $carteira = new Carteira();
            $carteira->cadastraCarteira($usuario);
            DB::commit();
            return $token;
            //return ['usuario' => $usuario->toArray(), 'carteira' => $novaCarteira];
        } catch (Exception $erro) {
            DB::rollback();
            return Helper::buildJsonError($erro->getMessage());
        }
    }

    public function autenticaUsuario($request)
    {
        return \Auth::attempt(['sUsuario_email' => $request['sUsuario_email'], 'password' => $request['sUsuario_password']]);
    }
}
