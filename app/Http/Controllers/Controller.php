<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="MECHA API",
 *    version="1.0.0",
 *    description="API untuk Sistem Manajemen Bengkel Mobil"
 * )
 * @OA\Server(
 *      url="http://localhost:8000",
 *      description="API Server"
 * )
 * @OA\SecurityScheme(
 *      type="http",
 *      name="Token",
 *      in="header",
 *      scheme="bearer",
 *      description="Login dengan email dan password untuk dapatkan authentication token",
 *      securityScheme="token",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}