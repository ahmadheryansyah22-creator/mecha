<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(title: "MECHA API", version: "1.0.0", description: "API untuk Sistem Manajemen Bengkel Mobil")]
#[OA\Server(url: "http://localhost:8000", description: "API Server")]
#[OA\SecurityScheme(type: "http", name: "Token", in: "header", scheme: "bearer", securityScheme: "token")]
class OpenApi
{
}
