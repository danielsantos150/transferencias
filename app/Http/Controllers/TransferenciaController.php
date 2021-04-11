<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use App\Traits\ApiResponser;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransferenciaController extends Controller
{
    use ApiResponser;

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
            $response = $validator->errors();
            return $this->error(
                'Falha ao realizar a transferência.',
                Response::HTTP_BAD_REQUEST,
                $validator->errors()
            );
        }else{
            $transferencia = new Transferencia();
            $response = $transferencia->realizaTransferencia($request->input());
            return $this->success([
                'result' => $response["data"]
            ], $response["msg"]);
        }

    }
}
