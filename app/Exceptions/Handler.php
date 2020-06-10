<?php

namespace App\Exceptions;

use Throwable;
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof
                TokenExpiredException
            ) {
                return response()->json(['message' => 'Token has expired'], 401);
            } else if ($exception instanceof
                TokenBlacklistedException
            ) {
                return response()->json(['message' => 'Token has been blacklisted'], 401);
            } else if ($exception instanceof
                TokenInvalidException
            ) {
                return response()->json(['message' => 'Token is invalid'], 401);
            }
            if ($exception->getMessage() === 'Token not provided') {
                return response()->json(['message' => 'Token not provided'], 401);
            }
            if ($exception instanceof UnauthorizedHttpException) {
                return response()->json(['message' => $exception->getMessage()], $exception->getStatusCode());
            }
        }


        return parent::render($request, $exception);
    }
}
