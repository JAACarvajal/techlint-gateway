<?php

use App\Constants\HttpCodes;
use App\Exceptions\ExceptionHandler;
use App\Http\Middleware\{AuditLogMiddleware, CheckBearerToken};
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'audit.log'   => AuditLogMiddleware::class,
            'check.token' => CheckBearerToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            $className = get_class($e);
            $handlers = ExceptionHandler::$handlers;

            if (array_key_exists($className, $handlers)) {
                $method = $handlers[$className];
                $handler = new ExceptionHandler();

                return $handler->$method($e, $request);
            }

            return response()->json([
                'error' => [
                    'type'      => basename(get_class($e)),
                    'status'    => $e->getCode() ?: HttpCodes::INTERNAL_SERVER_ERROR,
                    'message'   => $e->getMessage() ?: 'An unexpected error occurred',
                    'timestamp' => now()->toISOString(),
                    'debug' => app()->environment('local') ? [
                        'file'  => $e->getFile(),
                        'line'  => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ] : null
                ]
            ], $e->getCode() ?: HttpCodes::INTERNAL_SERVER_ERROR);
        });
    })->create();
