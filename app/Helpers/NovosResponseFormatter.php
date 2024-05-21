<?php 

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

final class NovosResponseFormatter
{
    public static function formatSuccess(array $data, int $code): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'timestamp' => now()
        ], $code);
    }

    public static function formatError(array $error, int $code): JsonResponse
    {
        return response()->json([
            'error' => $error,
        ],$code);
    }
}