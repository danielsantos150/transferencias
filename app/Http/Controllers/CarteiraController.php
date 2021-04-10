<?php

namespace App\Http\Controllers;

use App\Http\Helpers\MensagensHelper;
use App\Models\Carteira;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CarteiraController extends Controller
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
     * Função responsável por realizar o depósito de dinheiro no saldo da carteira do usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    }

    /**
     * Função responsável por realizar o depósito de dinheiro no saldo da carteira do usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $iCarteira_id)
    {
        $validator = Validator::make($request->all(), [
            'fSaldo_carteira' => 'required|numeric|between:0.00,999999999999999.99',
        ]);
        if ($validator->fails()) {
            $response = Helper::buildJson(Response::HTTP_BAD_REQUEST, Helper::MESSAGE_ERROR, $validator->errors());
        }else{
            $carteira = new Carteira();
            $response = $carteira->depositaSaldo($request->input(), $iCarteira_id);
        }
        return response()->json($response, $response['code']);
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
