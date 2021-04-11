<?php

namespace App\Models;

use Exception;
use Helper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'tb_usuarios';

    protected $primaryKey = 'iUsuario_id';

    protected $fillable = [
        'sUsuario_nome',
        'sUsuario_cpf',
        'password',
        'sUsuario_email',
        'iTipo_usuario_id',
    ];

    public function cadastraUsuario($request)
    {
        try {
            DB::beginTransaction();
            $usuario = User::create([
                'sUsuario_nome' => $request['sUsuario_nome'],
                'sUsuario_cpf' => $request['sUsuario_cpf'],
                'sUsuario_email' => $request['sUsuario_email'],
                'password' => bcrypt($request['password']),
                'iTipo_usuario_id' => $request['iTipo_usuario_id']
            ]);
            $token = $usuario->createToken('API Token')->plainTextToken;
            $carteira = new Carteira();
            $carteira->cadastraCarteira($usuario);
            DB::commit();
            return $token;
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
