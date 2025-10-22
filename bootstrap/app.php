<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 🔹 Validation errors (422)
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // 🔹 Unauthenticated (401)
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }
        });

        // 🔹 Model not found (404)
        $exceptions->render(function (ModelNotFoundException|NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Resource not found',
                ], 404);
            }
        });

        // 🔹 Catch all (500)
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        });
    })->create();
