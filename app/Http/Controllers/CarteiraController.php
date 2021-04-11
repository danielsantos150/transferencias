<?php

namespace App\Http\Controllers;

use App\Models\Carteira;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CarteiraController extends Controller
{
    use ApiResponser;

    /**
     * Função responsável por realizar o depósito de dinheiro
     * no saldo da carteira do usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $iCarteira_id)
    {
        $validator = Validator::make($request->all(), [
            'fSaldo_carteira' => 'required|numeric|between:0.00,9999999999.99',
        ]);
        if ($validator->fails()) {
            return $this->error(
                'Falha ao realizar a transferência.',
                Response::HTTP_BAD_REQUEST,
                $validator->errors()
            );
        }
        $carteira = new Carteira();
        $response = $carteira->depositaSaldo($request->input(), $iCarteira_id);
        return $this->success([
            'result' => $response,
        ], 'Depósito registrado com sucesso.');
    }
}
