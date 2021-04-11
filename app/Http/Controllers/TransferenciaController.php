<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * O request dessa função receberá o iCarteira_id da fonte pagadora e o iUsuario_id beneficiário da transferencia, além do valor a ser transferido.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iCarteira_id' => 'required|numeric|exists:tb_carteira',
            'iUsuario_id' => 'required|numeric|exists:tb_usuarios',
            'fValor_transferido' => 'required|numeric|between:0.00,999999999999999.99',
        ]);
        if ($validator->fails()) {
            $response = Helper::buildJson(Response::HTTP_BAD_REQUEST, Helper::MESSAGE_ERROR, $validator->errors());
        }else{
            $transferencia = new Transferencia();
            $response = $transferencia->realizaTransferencia($request->input());
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
