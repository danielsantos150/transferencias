<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends Controller
{
    protected $response = ['code' => '', 'message' => '', 'result' => ''];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sUsuario_nome' => 'required|string|max:100',
            'sUsuario_cpf' => 'required|numeric|unique:tb_usuarios|digits_between:11,14',
            'sUsuario_email' => 'required|email|unique:tb_usuarios|max:50',
            'sUsuario_password' => 'required|string|min:8|max:256',
            'iTipo_usuario_id' => 'required|integer|in:1,2',
        ]);
        if ($validator->fails()) {
            $this->response['code'] = Response::HTTP_BAD_REQUEST;
            $this->response['message'] = Helper::MESSAGE_ERROR;
            $this->response['result'] = $validator->errors();
            return response()->json($this->response, $this->response['code']);
        }

        $usuario = new Usuario();
        $this->response['code'] = Response::HTTP_CREATED;
        $this->response['message'] = Helper::MESSAGE_OK;
        $this->response['result'] = $usuario->cadastraUsuario($request->input());
        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
