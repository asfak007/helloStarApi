<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(

      api: array_merge(
            glob(base_path('routes/api/v1/*.php')) // load all files in routes/api/v1/
        ),
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        //  Global middleware (runs on every request)
        $middleware->web([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);

        //  Define custom middleware aliases
        $middleware->alias([
            'api.auth' => \App\Http\Middleware\ApiAuthenticate::class, // your custom auth middleware
            'ip.throttle' => \App\Http\Middleware\IpThrottleMiddleware::class,
            'role' => \App\Http\Middleware\CheckUserRole::class,
        ]);

        //  Define middleware groups
        $middleware->group('api', [
            'ip.throttle',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Throwable $e, $request) {

            // Apply only for your API routes
            if (! $request->is('api/v1') && ! $request->is('api/v1/*')) {
                return null; // Let Laravel handle web exceptions normally
            }

            // Authentication
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            // Validation errors
            if ($e instanceof ValidationException) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            // Default response
            $status = 500;
            $message = 'Internal Server Error';

            // HTTP-related exceptions
            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = match ($status) {
                    404 => 'Resource/URL not found',
                    405 => 'Method not allowed',
                    403 => 'Forbidden',
                    default => $e->getMessage() ?: $message,
                };
            } elseif ($e instanceof NotFoundHttpException) {
                $status = 404;
                $message = 'Resource/URL not found';
            }

            // Log 5xx errors
            if ($status >= 500) {
                    Log::error($e->getMessage(), [
                    'exception' => $e,
                    'url' => $request->fullUrl(),
                ]);
            }

            // Final JSON response
            $payload = [
                'status' => $status,
                'message' => $message,
            ];

            if (config('app.debug')) {
                $payload['error'] = $e->getMessage();
                $payload['trace'] = collect($e->getTrace())->take(3);
            }

            return response()->json($payload, $status);
        });
    })
    ->create();
