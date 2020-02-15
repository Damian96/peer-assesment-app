<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @see: https://github.com/octobercms/october/issues/4359
     * @var array
     */
    protected $dontReport = [
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
//        \Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException::class,
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
     * Determine if the exception should be reported.
     *
     * @param \Exception $e
     * @return bool
     */
    public function shouldReport(Exception $e)
    {
        return !in_array(get_class($e), $this->dontReport, true);
    }

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            return parent::render($request, $exception);
        } else {
            return parent::render($request, new NotFoundHttpException('The requested page was not found'));
        }
    }
}
