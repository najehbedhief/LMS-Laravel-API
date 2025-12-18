<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ApiHandler
{
    use ApiResponse;

    public function __invoke(Throwable $e, Request $request)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return $this->errorResponse('Resource not found', 404);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse('Method not allowed', 405);
            }

            if ($e instanceof AuthenticationException) {
                return $this->errorResponse('Unauthenticated', 401);
            }

            if ($e instanceof AuthorizationException) {
                return $this->errorResponse('Forbidden', 403);
            }

            if ($e instanceof ThrottleRequestsException) {
                return $this->errorResponse('Too many requests', 429);
            }

            if ($e instanceof ValidationException) {
                return $this->errorResponse(
                    'Validation failed',
                    422,
                    $e->errors()
                );
            }
             // DUPLICATE KEY / UNIQUE CONSTRAINT
            if ($e instanceof QueryException) {
                if ($e->getCode() === '23000') {
                    return $this->errorResponse(
                        'Duplicate resource or already exists',
                        409
                    );
                }
            }

            return $this->errorResponse('Server error', 500);
        }

        return null;
    }
}