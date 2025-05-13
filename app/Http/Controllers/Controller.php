<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Sistema de Renta de Apartamentos - API",
 *     version="1.0.0",
 *     description="Documentación de la API del sistema de rentas de apartamentos. Permite gestionar usuarios, apartamentos, quejas, contratos y más.",
 *     @OA\Contact(
 *         email="tucorreo@ejemplo.com"
 *     ),
 *     @OA\License(
 *         name="Todos los derechos reservados - Universidad Piloto de Colombia"
 *     )
 * )
 */

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
