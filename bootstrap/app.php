<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', [
            SecurityHeaders::class,
        ]);

        $middleware->alias([
            'role'   => CheckRole::class,
            'active' => EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Let Laravel handle validation redirects / JSON validation payloads (field errors only).
            if ($e instanceof ValidationException) {
                return null;
            }

            // Unauthenticated web users: default redirect to login; JSON gets a safe message.
            if ($e instanceof AuthenticationException) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Please sign in to continue.'], 401);
                }

                return null;
            }

            // JSON: never expose exception messages, paths, or hostnames — only fixed, user-safe text.
            if ($request->expectsJson()) {
                $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
                if ($status < 400 || $status > 599) {
                    $status = 500;
                }

                $messages = [
                    400 => 'We could not process that request.',
                    401 => 'Please sign in to continue.',
                    403 => 'You are not allowed to do that.',
                    404 => 'We could not find what you asked for.',
                    405 => 'That action is not available for this page.',
                    408 => 'The request took too long. Please try again.',
                    409 => 'This request could not be completed.',
                    413 => 'The file or data sent was too large.',
                    422 => 'Some information needs to be corrected.',
                    429 => 'Too many attempts. Please wait a moment and try again.',
                    500 => 'Something went wrong on our side. Please try again later.',
                    502 => 'The service is temporarily unavailable. Please try again later.',
                    503 => 'The system is temporarily unavailable. Please try again shortly.',
                ];

                $message = $messages[$status] ?? 'Something went wrong. Please try again.';

                return response()->json(['message' => $message], $status);
            }

            // Web (HTML): when debug is off, never render exception messages or stack traces to users.
            if (! config('app.debug')) {
                $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
                if ($status < 400 || $status > 599) {
                    $status = 500;
                }

                $viewMap = [
                    403 => 'errors.403',
                    404 => 'errors.404',
                    419 => 'errors.419',
                    429 => 'errors.429',
                    503 => 'errors.503',
                ];

                if (isset($viewMap[$status]) && view()->exists($viewMap[$status])) {
                    return response()->view($viewMap[$status], [], $status);
                }

                if ($status >= 500) {
                    return response()->view('errors.500', [], $status);
                }

                return response()->view('errors.generic-client', [], $status);
            }

            return null;
        });
    })->create();
