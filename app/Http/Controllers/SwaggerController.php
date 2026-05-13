<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="MECHA API Manajemen Bengkel Mobil",
 *    contact={
 *        "name": "MECHA Support",
 *        "email": "support@mecha.com"
 *    },
 *    license={
 *        "name": "MIT"
 *    }
 * )
 * @OA\Server(
 *    url="http://localhost:8000",
 *    description="Development Server"
 * )
 * @OA\Server(
 *    url="https://api.mecha.com",
 *    description="Production Server"
 * )
 * @OA\SecurityScheme(
 *    type="http",
 *    name="Token",
 *    in="header",
 *    scheme="bearer",
 *    description="Login dengan email dan password untuk dapatkan authentication token",
 *    securityScheme="token"
 * )
 */
class ApiDocumentation
{
    // Dummy class untuk dokumentasi Swagger
}