<?php

namespace App\Exceptions;

use App\Http\ResponseTrait;
use App\Repositories\RepositoryException;
use App\Services\Courier\CourierServiceException;
use App\Services\CRM\CRMServiceException;
use App\Services\Web\CatalogServiceException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

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
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {

            return $this->responseError('Не найдено!', Response::HTTP_NOT_FOUND, null, get_class($exception));
        }

        if ($exception instanceof AuthenticationException) {
            return $this->responseError('Неавторизованный!', Response::HTTP_UNAUTHORIZED, null, get_class($exception));
        }


        if ($exception instanceof RepositoryException) {
            return $this->responseError('Ошибка обработки данных:' . $exception->getMessage(), 500, null, get_class($exception));
        }

        if ($exception instanceof CourierServiceException) {

            return $this->responseError($exception->getMessage(), 422, null, get_class($exception));
        }

        if ($exception instanceof CatalogServiceException) {

            return $this->responseError($exception->getMessage(), 422, null, get_class($exception));
        }

        if ($exception instanceof CRMServiceException) {

            if ($exception->getCode() === 423) {
                return $this->responseError($exception->getMessage(), 422, ['reason' => 'notBirthday'], get_class($exception));
            }
            return $this->responseError($exception->getMessage(), 422, null, get_class($exception));
        }

        if ($exception instanceof ValidationException) {

            return $this->responseError($exception->getMessage(), 422, $exception->errors(), get_class($exception));
        }


        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }
}
