<?php

namespace App\Exceptions;

use ErrorException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->wantsJson()) {
                $status = method_exists($e, 'getStatusCode') ? ($e->getStatusCode() ?: 500) : 500;
                $message = method_exists($e, 'getMessage') ? $e->getMessage() : Lang::get('messages/errors.server_error');

                if(App::isLocal()){
                    return response()->json([
                        'status' => $status,
                        'message' => $message
                    ],$status);
                }

                $response = [
                    'status' => 500,
                    'message' => Lang::get('messages/errors.server_error')
                ];

                if ($e instanceof ValidationException) {
                    $response['status'] = 400;
                    $response['message'] = $message;
                } else if ($e instanceof AuthenticationException) {
                    $response['status'] = 401;
                    $response['message'] = Lang::get('messages/errors.authentication');
                } else if ($e instanceof ErrorException) {
                    $response['status'] = $status;
                    $response['message'] = $message;
                }else if($e instanceof AccessDeniedHttpException){
                    $response['status'] = 403;
                    $response['message'] = Lang::get('messages/errors.forbidden');
                }

                return response()->json($response, $status);
            }
        });

    }
}
