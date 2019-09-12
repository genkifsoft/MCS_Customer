<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use MicroService\Src\Entity\Json\BasicEntity;


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
        $handerError = new BasicEntity();
        $handerError->setVerifyCode(1);

        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            $handerError->setStatus($exception->getStatusCode());
            if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
            {
                $handerError->setMessage('TOKEN_EXPIRED');
                return $handerError->toJson();
            }
            else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
            {
                $handerError->setMessage('TOKEN_INVALID');
                return $handerError->toJson();
            }
            else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                $handerError->setMessage('TOKEN_BLACKLISTED');
                return $handerError->toJson();
            } else {
                $handerError->setMessage('AUTHORIZATION_TOKEN_NOT_FOUND');
                $handerError->setStatus(404);
                return $handerError->toJson();
            }
        }
        if ($exception->getMessage() === 'Token not provided')
        {
            $handerError->setMessage('TOKEN_NOT_PROVIDED');
            $handerError->setStatus(401);
            return $handerError->toJson();
        }
        return parent::render($request, $exception);
    }
}
