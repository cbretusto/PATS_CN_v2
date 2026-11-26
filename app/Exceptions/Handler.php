<?php

namespace App\Exceptions;

<<<<<<< HEAD
use Exception;
=======
use Throwable;
>>>>>>> origin/laravel-10-php-8.4.27
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
<<<<<<< HEAD
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
=======
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
>>>>>>> origin/laravel-10-php-8.4.27
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
<<<<<<< HEAD
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
=======
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
>>>>>>> origin/laravel-10-php-8.4.27
    {
        return parent::render($request, $exception);
    }
}
