<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
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

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
        $this->renderable(function (Throwable $e, $request) {
            return $this->handleExceptions($e, $request);
        });
    }


    public function handleExceptions(Throwable $e, $request)
    {
        //401 Unauthorized
        if ($e instanceof AuthenticationException && $request->expectsJson()) {
            return $this->errorResponse([], 'Authentication failure', Response::HTTP_UNAUTHORIZED);
        }
        //404 Not Found
        if ($e instanceof NotFoundHttpException && $request->expectsJson()) {
            return $this->errorResponse([], 'Not found', Response::HTTP_NOT_FOUND);
        }
        //405 Method Not Allowed
        if ($e instanceof MethodNotAllowedException && $request->expectsJson()) {
            return $this->errorResponse([], 'Method not allowed exception', Response::HTTP_METHOD_NOT_ALLOWED);
        }
        //400 & 422 Unprocessable Entity
        if ($e instanceof ValidationException && $request->expectsJson()) {
            return $this->errorResponse(['errors' => $e->validator->getMessageBag()], 'Incorrect data', $e->status);
        }
        //403 Forbidden
        if ($e instanceof AccessDeniedHttpException && $request->expectsJson()) {
            return $this->errorResponse([], 'This action is unauthorized',Response::HTTP_FORBIDDEN);
        }

        //429 Too Many Requests
        if ($e instanceof ThrottleRequestsException && $request->expectsJson()) {
            return $this->errorResponse([], 'Too many requests', Response::HTTP_TOO_MANY_REQUESTS);
        }

    }


    public function errorResponse($data, $message = null, $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json(['status' => 'failed', 'message' => $message, 'data' => $data], $code);
    }
}
