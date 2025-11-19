<?php

namespace App\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }

        if ($e instanceof ValidationException) {
            return ApiResponse::fail(
                'VALIDATION_ERROR',
                'Data yang dikirim tidak valid.',
                422,
                $e->errors()
            );
        }

        if ($e instanceof ModelNotFoundException) {
            $model = class_basename($e->getModel());

            return ApiResponse::fail(
                'NOT_FOUND',
                "{$model} tidak ditemukan.",
                404
            );
        }

        if ($e instanceof NotFoundHttpException) {
            return ApiResponse::fail(
                'ROUTE_NOT_FOUND',
                'Endpoint tidak ditemukan.',
                404
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponse::fail(
                'METHOD_NOT_ALLOWED',
                'Metode HTTP tidak diizinkan untuk endpoint ini.',
                405
            );
        }

        if ($e instanceof AuthorizationException) {
            return ApiResponse::fail(
                'FORBIDDEN',
                'Anda tidak memiliki izin untuk mengakses resource ini.',
                403
            );
        }

        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            $message = $e->getMessage() ?: 'Terjadi kesalahan.';

            return ApiResponse::fail(
                'HTTP_ERROR',
                $message,
                $status
            );
        }

        if (config('app.debug')) {
            return ApiResponse::fail(
                'SERVER_ERROR',
                $e->getMessage(),
                500,
                [
                    'exception' => class_basename($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );
        }

        return ApiResponse::fail(
            'SERVER_ERROR',
            'Terjadi kesalahan pada server.',
            500
        );
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return ApiResponse::fail(
            'UNAUTHENTICATED',
            'Anda belum terautentikasi.',
            401
        );
    }
}
