<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, \Throwable $exception)
    {
        // This will replace our 404 response with
        // a JSON response.
        if ($exception instanceof ModelNotFoundException) {
            $response =  response()->json([
                'error' => 'Resource not found'
            ], 404);
        }
        else if ($exception instanceof NotFoundHttpException) {
            $response = response()->json([
                'error' => 'Page not found'
            ], 404);
        }
        else if ($exception instanceof HttpException) {
            $response =  response()->json([
                'error' => $exception->getMessage()
            ], $exception->getStatusCode());
        }

        if (isset($response))
        {
            return $response;
        }
        else {
            return parent::render($request, $exception);
        }
    }
}
