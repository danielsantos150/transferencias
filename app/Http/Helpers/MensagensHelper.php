<?php

namespace App\Http\Helpers;

use Symfony\Component\HttpFoundation\Response;

class MensagensHelper
{
    public const MESSAGE_OK = "OK";
    public const MESSAGE_ERROR = "Houve uma falha ao realizar a requisição.";
    public const MESSAGE_FAIL = "FAIL";
    public const MESSAGE_SUCCESS = "Transferência realizada com sucesso.";

    public $response = ['code' => '', 'message' => '', 'result' => ''];

    public static function buildJson($http_type, $type_result, $result){
        $response['code'] = $http_type;
        $response['message'] = $type_result;
        $response['result'] = $result;
        return $response;
    }
}
