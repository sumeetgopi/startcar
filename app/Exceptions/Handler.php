<?php

namespace App\Exceptions;

use ArrayObject;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $data = new ArrayObject();
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof TokenExpiredException) {
                $message = __('message.token_expired');
                return apiResponse(0, $message, $data);
                // return response()->json(['error' => 'TOKEN_EXPIRED']);
            }
            else if ($preException instanceof TokenInvalidException) {
                $message = __('message.token_invalid');
                return apiResponse(0, $message, $data);
                // return response()->json(['error' => 'TOKEN_INVALID']);
            }
            else if ($preException instanceof TokenBlacklistedException) {
                $message = __('message.token_blacklisted');
                return apiResponse(0, $message, $data);
                // return response()->json(['error' => 'TOKEN_BLACKLISTED']);
            }
        }

        if ($exception->getMessage() === 'Token not provided') {
            $message = __('message.token_not_provided');
            return apiResponse(0, $message, $data);
            // return response()->json(['error' => 'Token not provided']);
        }
        return parent::render($request, $exception);
    }
}
