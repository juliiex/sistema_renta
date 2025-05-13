<?php

namespace App\Http\Controllers;

use App\Models\RolPermiso;
use Illuminate\Http\Request;

class RolPermisoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rol-permiso",
     *     tags={"RolPermiso"},
     *     summary="Listar todas las relaciones rol-permiso",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de relaciones rol-permiso",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/RolPermiso"))
     *     )
     * )
     */
    public function index()
    {
        $rolPermisos = RolPermiso::with('rol', 'permiso')->get();
        return response()->json($rolPermisos);
    }

    /**
     * @OA\Get(
     *     path="/api/rol-permiso/{id}",
     *     tags={"RolPermiso"},
     *     summary="Obtener una relación rol-permiso específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la relación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Relación encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/RolPermiso")
     *     ),
     *     @OA\Response(response=404, description="Relación no encontrada")
     * )
     */
    public function show($id)
    {
        $rolPermiso = RolPermiso::find($id);
        if ($rolPermiso) {
            return response()->json($rolPermiso);
        }
        return response()->json(['message' => 'Relación de rol y permiso no encontrada'], 404);
    }

    /**
     * @OA\Get(
     *     path="/api/rol-permiso/create",
     *     tags={"RolPermiso"},
     *     summary="Mostrar mensaje de creación de rol-permiso (solo demostrativo)",
     *     @OA\Response(
     *         response=200,
     *         description="Mensaje de formulario de creación"
     *     )
     * )
     */
    public function create()
    {
        return response()->json(['message' => 'Formulario de creación de rol-permiso']);
    }

    /**
     * @OA\Post(
     *     path="/api/rol-permiso",
     *     tags={"RolPermiso"},
     *     summary="Crear una nueva relación rol-permiso",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rol_id","permiso_id"},
     *             @OA\Property(property="rol_id", type="integer", example=1),
     *             @OA\Property(property="permiso_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Relación rol-permiso creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RolPermiso")
     *     ),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:rol,id',
            'permiso_id' => 'required|exists:permiso,id',
        ]);

        $rolPermiso = RolPermiso::create($request->all());
        return response()->json($rolPermiso, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/rol-permiso/{id}",
     *     tags={"RolPermiso"},
     *     summary="Actualizar una relación rol-permiso existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la relación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rol_id", "permiso_id"},
     *             @OA\Property(property="rol_id", type="integer", example=1),
     *             @OA\Property(property="permiso_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Relación actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RolPermiso")
     *     ),
     *     @OA\Response(response=404, description="Relación no encontrada"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function update(Request $request, $id)
    {
        $rolPermiso = RolPermiso::find($id);
        if ($rolPermiso) {
            $request->validate([
                'rol_id' => 'required|exists:rol,id',
                'permiso_id' => 'required|exists:permiso,id',
            ]);

            $rolPermiso->update($request->all());
            return response()->json($rolPermiso);
        }
        return response()->json(['message' => 'Relación de rol y permiso no encontrada'], 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/rol-permiso/{id}",
     *     tags={"RolPermiso"},
     *     summary="Eliminar una relación rol-permiso",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la relación a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Relación eliminada exitosamente"
     *     ),
     *     @OA\Response(response=404, description="Relación no encontrada")
     * )
     */
    public function destroy($id)
    {
        $rolPermiso = RolPermiso::find($id);
        if ($rolPermiso) {
            $rolPermiso->delete();
            return response()->json(['message' => 'Relación de rol y permiso eliminada correctamente']);
        }
        return response()->json(['message' => 'Relación de rol y permiso no encontrada'], 404);
    }
}
