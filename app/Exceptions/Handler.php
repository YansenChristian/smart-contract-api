<?php

namespace App\Exceptions;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Rollbar\Rollbar;
use Rollbar\RollbarLogger;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);

        #Initialize Rollbar Logger if provider exists
        if(app()->bound(RollbarLogger::class)) {
            app(RollbarLogger::class);
        }

        $code = $exception->getCode();
        $message = $exception->getMessage();
        if($code == 422 || $code == 401) {
            Rollbar::info($message);
        } else {
            $exception instanceof \RuntimeException
                ? Rollbar::critical($message)
                : Rollbar::error($message);
        }

    }

    public function render($request, Exception $exception)
    {
        dd($exception);
        if($exception instanceof RLException) {
            return $exception->getResponse();
        }

        if($exception->getCode() == 403 || $exception instanceof SignatureInvalidException) {
            $exception = new AuthenticationException();
            return $exception->getResponse();
        }

        if($exception instanceof ClientException) {
            return $exception->getMessage();
        }

        if($exception instanceof ExpiredException) {
            $exception = new AuthenticationException('Authentication failed due to expired token.');
            return $exception->getResponse();
        }

        if($exception instanceof NotFoundHttpException) {
            $exception = new RouteException();
            return $exception->getResponse();
        }

        if(env('APP_DEBUG', config('app.debug', false))) {
            return parent::render($request, $exception);
        }

        $exception = new GeneralException();
        return $exception->getResponse();
    }
}
