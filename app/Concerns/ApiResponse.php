<?php

namespace App\Concerns;

use App\Constants\HttpCodes;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Returns a JSON response with a success message and data
     *
     * @param mixed $data Response data
     * @param string $message Response message
     * @param int $code HTTP status code
     */
    protected static function responseWithMessage(mixed $data = [], string $message = 'success', int $code = HttpCodes::OK): JsonResponse
    {
        return self::responseSuccess([
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Returns a JSON response with data
     *
     * @param mixed $data Response data
     * @param int $code HTTP status code
     */
    protected static function responseSuccess(mixed $data = [], int $code = HttpCodes::OK): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Returns a JSON error response with message
     *
     * @param array $message Response message
     * @param int $code HTTP status code
     */
    protected static function responseError(string $message = 'Something went wrong', int $code = HttpCodes::INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(['error' => $message], $code);
    }
}
