<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="EduApp Mobile API",
 *     version="1.0.0",
 *     description="Auth + Profile endpoints for the mobile client."
 *   ),
 *   @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Current server"
 *   )
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="BearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="Token"
 * )
 */
class OpenApi {}
