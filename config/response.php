<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function ok(mixed $data = null, mixed $meta = null, int $status = 200): JsonResponse
    {
        $res = ['ok' => true, 'data' => self::toArray($data)];
        if (!is_null($meta)) $res['meta'] = self::toArray($meta);
        return response()->json($res, $status);
    }

    public static function fail(string $code, string $message, int $status = 400, mixed $details = null): JsonResponse
    {
        $err = [
            'ok' => false,
            'error' => ['code' => $code, 'message' => $message],
        ];
        if (!is_null($details)) $err['error']['details'] = self::toArray($details);
        return response()->json($err, $status);
    }

    private static function toArray(mixed $v): mixed
    {
        return $v instanceof Arrayable ? $v->toArray() : $v;
    }
}
