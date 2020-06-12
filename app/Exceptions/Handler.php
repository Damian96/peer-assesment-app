<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json(['error' => 'Entry for ' . str_replace('App\\', '', $exception->getModel()) . ' not found'], 404);
        } elseif ($exception instanceof UnauthorizedHttpException && $request->wantsJson()) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
//        elseif ($exception instanceof \RuntimeException && $request->wantsJson()) {

        if (Auth::guard('web')->check() && Auth::user()->isStudent()) {
            if ($exception instanceof NotFoundHttpException)
                return parent::render($request, $exception);
            else
                throw new UnauthorizedHttpException('Forbidden');
        }

        if ($this->shouldReport($exception)) {
            return parent::render($request, $exception);
        } else {
            return parent::render($request, new NotFoundHttpException('The requested page was not found'));
        }
    }
}
