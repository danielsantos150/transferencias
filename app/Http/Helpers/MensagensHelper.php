<?php

namespace App\Http\Helpers;

use Helper;
use Symfony\Component\HttpFoundation\Response;

class MensagensHelper
{
    public const MESSAGE_OK = 'OK';
    public const MESSAGE_ERROR = 'Houve uma falha ao realizar a requisição.';
    public const MESSAGE_FAIL = 'FAIL';
    public const MESSAGE_SUCCESS = 'Transferência realizada com sucesso e beneficiário notificado.';
    public const MESSAGE_TRANSACTION = 'Houve uma falha ao realizar a transferência, os valores foram estornados ao pagador.';
    public const MESSAGE_NOTIFY_ERROR = 'Transferência realizaca com sucesso, mas serviço de notificação inacessível.';

    public $response = ['code' => '', 'message' => '', 'result' => ''];

    public static function buildJson($http_type, $type_result, $result)
    {
        $response['code'] = $http_type;
        $response['message'] = $type_result;
        $response['result'] = $result;
        return $response;
    }

    public static function buildJsonError($error_msg)
    {
        return Helper::buildJson(Response::HTTP_INTERNAL_SERVER_ERROR, Helper::MESSAGE_ERROR, ['error' => $error_msg]);
    }
}
