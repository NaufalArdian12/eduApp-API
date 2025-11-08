<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Success Response
     *
     * @param mixed $data
     * @param mixed $meta
     * @param int   $status HTTP status code
     */
    public static function ok(mixed $data = null, mixed $meta = null, int $status = 200): JsonResponse
    {
        $res = [
            'status' => 'success',
            'data' => self::toArray($data),
        ];

        if (!is_null($meta)) {
            $res['meta'] = self::toArray($meta);
        }

        return response()->json($res, $status);
    }

    /**
     * Error Response
     *
     * @param string      $code   Business error code
     * @param string      $message
     * @param int         $status HTTP status code
     * @param mixed|null  $details
     */
    public static function fail(
        string $code,
        string $message,
        int $status = 400,
        mixed $details = null
    ): JsonResponse {
        $err = [
            'status' => 'error',
            'error' => [
                'message' => $message,
            ],
        ];

        if (!is_null($details)) {
            $err['error']['details'] = self::toArray($details);
        }

        return response()->json($err, $status);
    }

    /** Helper to convert models/resources to array */
    private static function toArray(mixed $v): mixed
    {
        return $v instanceof Arrayable ? $v->toArray() : $v;
    }
}
