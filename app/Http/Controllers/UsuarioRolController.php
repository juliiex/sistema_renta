<?php

namespace App\Http\Controllers;

use App\Models\UsuarioRol;
use Illuminate\Http\Request;

class UsuarioRolController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/usuario-rol",
     *     summary="Lista todas las relaciones usuario-rol",
     *     tags={"UsuarioRol"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de relaciones usuario-rol"
     *     )
     * )
     */
    public function index()
    {
        $usuarioRoles = UsuarioRol::with('usuario', 'rol')->get();
        return response()->json($usuarioRoles);
    }

    /**
     * @OA\Get(
     *     path="/api/usuario-rol/{id}",
     *     summary="Mostrar una relación usuario-rol específica",
     *     tags={"UsuarioRol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la relación usuario-rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Relación encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Relación no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $usuarioRol = UsuarioRol::find($id);
        if ($usuarioRol) {
            return response()->json($usuarioRol);
        }
        return response()->json(['message' => 'Relación de usuario y rol no encontrada'], 404);
    }

    /**
     * @OA\Get(
     *     path="/api/usuario-rol/create",
     *     summary="Formulario de creación de usuario-rol",
     *     tags={"UsuarioRol"},
     *     @OA\Response(
     *         response=200,
     *         description="Mensaje de formulario de creación"
     *     )
     * )
     */
    public function create()
    {
        return response()->json(['message' => 'Formulario de creación de usuario-rol']);
    }

    /**
     * @OA\Post(
     *     path="/api/usuario-rol",
     *     summary="Crear una nueva relación usuario-rol",
     *     tags={"UsuarioRol"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"usuario_id", "rol_id"},
     *             @OA\Property(property="usuario_id", type="integer"),
     *             @OA\Property(property="rol_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Relación creada exitosamente"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'rol_id' => 'required|exists:rol,id',
        ]);

        $usuarioRol = UsuarioRol::create($request->all());
        return response()->json($usuarioRol, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/usuario-rol/{id}",
     *     summary="Actualizar una relación usuario-rol existente",
     *     tags={"UsuarioRol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la relación usuario-rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"usuario_id", "rol_id"},
     *             @OA\Property(property="usuario_id", type="integer"),
     *             @OA\Property(property="rol_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Relación actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Relación no encontrada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $usuarioRol = UsuarioRol::find($id);
        if ($usuarioRol) {
            $request->validate([
                'usuario_id' => 'required|exists:usuario,id',
                'rol_id' => 'required|exists:rol,id',
            ]);

            $usuarioRol->update($request->all());
            return response()->json($usuarioRol);
        }
        return response()->json(['message' => 'Relación de usuario y rol no encontrada'], 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/usuario-rol/{id}",
     *     summary="Eliminar una relación usuario-rol",
     *     tags={"UsuarioRol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la relación usuario-rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Relación eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Relación no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $usuarioRol = UsuarioRol::find($id);
        if ($usuarioRol) {
            $usuarioRol->delete();
            return response()->json(['message' => 'Relación de usuario y rol eliminada correctamente']);
        }
        return response()->json(['message' => 'Relación de usuario y rol no encontrada'], 404);
    }
}
