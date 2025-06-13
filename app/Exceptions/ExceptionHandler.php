<?php
namespace App\Exceptions;

use App\Concerns\ApiResponse;
use App\Constants\HttpCodes;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\{AccessDeniedHttpException, HttpException, NotFoundHttpException};
use Throwable;

class ExceptionHandler
{
    use ApiResponse;

    /**
     * Map of exception classes to their handler methods
     */
    public static array $handlers = [
        AccessDeniedHttpException::class => 'handleAuthenticationException',
        AuthenticationException::class   => 'handleAuthenticationException',
        AuthorizationException::class    => 'handleAuthorizationException',
        HttpException::class             => 'handleHttpException',
        ModelNotFoundException::class    => 'handleNotFoundException',
        NotFoundHttpException::class     => 'handleNotFoundException',
        ValidationException::class       => 'handleValidationException',
        ValidationException::class       => 'handleValidationException',
        QueryException::class            => 'handleQueryException',
    ];

    /**
     * Handle authentication exceptions
     */
    public function handleAuthenticationException(AuthenticationException|AccessDeniedHttpException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'Authentication error');

        return self::responseError([
            'error' => [
                'type'      => $this->getExceptionClass($e),
                'status'    => HttpCodes::UNAUTHORIZED,
                'message'   => $e->getMessage() ?? 'Authentication is required to access this resource.',
                'timestamp' => now()->toISOString(),
            ]
        ], HttpCodes::UNAUTHORIZED);
    }

    /**
     * Handle authentication exceptions
     */
    public function handleAuthorizationException(AuthorizationException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'Authorization error');

        return self::responseError([
            'error' => [
                'type'      => $this->getExceptionClass($e),
                'status'    => HttpCodes::FORBIDDEN,
                'message'   => $e->getMessage() ?? 'You do not have permission to perform this action.',
                'timestamp' => now()->toISOString(),
            ]
        ], HttpCodes::FORBIDDEN);
    }

    /**
     * Handle not found exceptions
     */
    public function handleNotFoundException(ModelNotFoundException|NotFoundHttpException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'Resource not found error');

        $message = $e instanceof ModelNotFoundException
            ? 'The requested resource was not found.'
            : "The requested endpoint '{$request->getRequestUri()}' was not found.";

        return self::responseError([
            'error' => [
                'type'      => $this->getExceptionClass($e),
                'status'    => HttpCodes::NOT_FOUND,
                'message'   => $message,
                'timestamp' => now()->toISOString(),
            ]
        ], HttpCodes::NOT_FOUND);
    }

    /**
     * Handle general HTTP exceptions
     */
    public function handleHttpException(HttpException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'HTTP error');

        return self::responseError([
            'error' => [
                'type'      => $this->getExceptionClass($e),
                'status'    => $e->getStatusCode(),
                'message'   => $e->getMessage() ?? 'An HTTP error occurred.',
                'timestamp' => now()->toISOString(),
            ]
        ], $e->getStatusCode());
    }

    /**
     * Handle validation exceptions
     */
    public function handleValidationException(ValidationException $e,Request $request): JsonResponse
    {
        $errors = [];

        foreach ($e->errors() as $attribute => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'attribute' => $attribute,
                    'message'   => $message,
                ];
            }
        }

        $this->logException($e, 'Validation error', ['errors' => $errors]);

        return self::responseError([
            'error' => [
                'type'      => $this->getExceptionClass($e),
                'status'    => HttpCodes::UNPROCESSABLE_CONTENT,
                'message'   => 'The provided data is invalid.',
                'timestamp' => now()->toISOString(),
                'errors'    => $errors,
            ]
        ], HttpCodes::UNPROCESSABLE_CONTENT);
    }

    /**
     * Handle database query exceptions
     */
    public function handleQueryException(QueryException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'Database query error', ['sql' => $e->getSql()]);

        $errorCode = $e->errorInfo[1] ?? null;

        switch ($errorCode) {
            case 1062:
                return response()->json([
                    'error' => [
                        'type'      => $this->getExceptionClass($e),
                        'status'    => 409,
                        'message'   => $e->getMessage() ?? 'A duplicate entry was found.',
                        'timestamp' => now()->toISOString(),
                    ]
                ], HttpCodes::CONFLICT);

            case 1451:
                return response()->json([
                    'error' => [
                        'type'      => $this->getExceptionClass($e),
                        'status'    => 409,
                        'message'   => $e->getMessage() ?? 'Cannot delete or update a parent row: a foreign key constraint fails.',
                        'timestamp' => now()->toISOString(),
                    ]
                ], HttpCodes::CONFLICT);

            default:
                return response()->json([
                    'error' => [
                        'type'      => $this->getExceptionClass($e),
                        'status'    => HttpCodes::INTERNAL_SERVER_ERROR,
                        'message'   => $e->getMessage() ?? 'A database error occurred. Please try again later.',
                        'timestamp' => now()->toISOString(),
                    ]
                ], HttpCodes::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the type of exception for logging and responses
     */
    private function getExceptionClass(Throwable $e): string
    {
        return basename(str_replace('\\', '/', get_class($e)));
    }

    /**
     * Log exception details for debugging
     */
    private function logException(Throwable $e, string $message, array $context = []): void
    {
        $logContext = array_merge([
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
            'url'       => request()->fullUrl(),
            'method'    => request()->method(),
            'ip'        => request()->ip(),
        ], $context);

        Log::warning($message, $logContext);
    }
}
