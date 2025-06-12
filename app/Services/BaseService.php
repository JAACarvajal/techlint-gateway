<?php

namespace App\Services;

use App\Concerns\ApiResponse;
use App\Constants\HttpCodes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BaseService
{
    use ApiResponse;

    /**
     * Handle exceptions and return a JSON response
     *
     * @param \Exception $exception Exception to handle
     * @param int $code Status code for the response, default is 500
     */
    public static function handleException(\Exception $exception, int $code = HttpCodes::INTERNAL_SERVER_ERROR): JsonResponse
    {
        self::logError($exception->getMessage());

        return self::responseError($exception->getMessage(), $code);
    }

    /**
     * Log an error message
     *
     * @param mixed $error
     */
    private static function logError(string $message): void
    {
        Log::error(message: 'Service Error: ' . $message);
    }
}
