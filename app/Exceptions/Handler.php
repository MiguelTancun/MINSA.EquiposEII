<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if (get_class($exception) == "Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException") {

            if ($exception->getPrevious() instanceof TokenExpiredException){
                $message = "El Token esta en una lista negra";
                return $this->errorResponse($message, $exception->getStatusCode(), 'TokenBlacklistedException');
            }
    
            else if ($exception->getPrevious() instanceof TokenInvalidException){
                $message = "El Token es incorrecto";
                return $this->errorResponse($message, $exception->getStatusCode(), 'TokenInvalidException');
            }
    
            else if ($exception->getPrevious() instanceof TokenExpiredException){
                $message = "El Token ha expirado";
                return $this->errorResponse($message, $exception->getStatusCode(), 'TokenExpiredException');
            }
    
            else if ($exception->getPrevious() instanceof JWTException){
                $message = "Error de JWT";
                return $this->errorResponse($message, $exception->getStatusCode(), 'JWTException');
            }
            else {
                return $this->errorResponse('Error', 401, 'Exception');
            }
        
        }

    }
}
