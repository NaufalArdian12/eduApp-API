<?php

use App\Support\ApiResponse;

if (!function_exists('ok')) {
    function ok(mixed $data = null, mixed $meta = null, int $status = 200) {
        return ApiResponse::ok($data, $meta, $status);
    }
}
if (!function_exists('fail')) {
    function fail(string $code, string $message, int $status = 400, mixed $details = null) {
        return ApiResponse::fail($code, $message, $status, $details);
    }
}
