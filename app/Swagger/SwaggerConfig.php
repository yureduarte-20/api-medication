<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="API de Medicamentos",
 *     version="1.0.0",
 *     description="API para gerenciamento de medicamentos e lembretes",
 *     @OA\Contact(
 *         email="suporte@exemplo.com",
 *         name="Equipe de Suporte"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 *
 * )
 *
 */

class SwaggerConfig
{

}
