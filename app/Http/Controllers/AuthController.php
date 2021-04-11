<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponser;

    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sUsuario_nome' => 'required|string|max:100',
            'sUsuario_cpf' => 'required|numeric|unique:tb_usuarios|digits_between:11,14',
            'sUsuario_email' => 'required|email|unique:tb_usuarios|max:50',
            'password' => 'required|string|min:8|max:256',
            'iTipo_usuario_id' => 'required|integer|in:1,2',
        ]);
        if ($validator->fails()) {
            return $this->error(
                Helper::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST, $validator->errors()
            );
        }
        $usuario = new User();
        $response = $usuario->cadastraUsuario($request->input());
        return $this->success([
            'token' => $response, 'token_type' => 'Bearer',
        ], 'Usuário registrado com sucesso.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sUsuario_email' => 'required|email|exists:tb_usuarios|max:50',
            'sUsuario_password' => 'required|string|min:8|max:256',
        ]);
        if ($validator->fails()) {
            return $this->error(
                Helper::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST, $validator->errors()
            );
        }
        $usuario = new User();
        if ($usuario->autenticaUsuario($request->input()) === false) {
            return $this->error('Credenciais Inválidas', 401);
        }
        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
        ], 'Usuário autenticado com sucesso.');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Tokens Revogados',
        ];
    }
}
