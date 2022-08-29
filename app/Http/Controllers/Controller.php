<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($status, $message = null, $result = null)
    {
        $response = [
           'status'=>$status
        ];

        if(!is_null($message)){
           $response['message'] = $message;
        }

        if (!is_null($result)) {
          $response['result'] = $result;
        }

        return response()->json($response, $response['status'], ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);
    }
}
