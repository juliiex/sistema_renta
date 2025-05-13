<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rol",
     *     summary="Listar todos los roles",
     *     tags={"Rol"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Rol"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Rol::all());
    }

    /**
     * @OA\Get(
     *     path="/api/rol/{id}",
     *     summary="Mostrar un rol específico",
     *     tags={"Rol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rol",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Rol")
     *     ),
     *     @OA\Response(response=404, description="Rol no encontrado")
     * )
     */
    public function show(Rol $rol)
    {
        return response()->json($rol);
    }

    /**
     * @OA\Get(
     *     path="/api/rol/create",
     *     summary="Vista de formulario de creación (placeholder)",
     *     tags={"Rol"},
     *     @OA\Response(
     *         response=200,
     *         description="Mensaje de formulario"
     *     )
     * )
     */
    public function create()
    {
        return response()->json(['message' => 'Formulario de creación de rol']);
    }

    /**
     * @OA\Post(
     *     path="/api/rol",
     *     summary="Crear un nuevo rol",
     *     tags={"Rol"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Rol")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Rol")
     *     ),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:rol,nombre',
        ]);

        $rol = Rol::create($validatedData);

        return response()->json($rol, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/rol/{id}",
     *     summary="Actualizar un rol",
     *     tags={"Rol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rol",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Rol")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Rol")
     *     ),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function update(Request $request, Rol $rol)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:rol,nombre,' . $rol->id,
        ]);

        $rol->update($validatedData);

        return response()->json($rol);
    }

    /**
     * @OA\Delete(
     *     path="/api/rol/{id}",
     *     summary="Eliminar un rol",
     *     tags={"Rol"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rol",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol eliminado correctamente"
     *     )
     * )
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();
        return response()->json(['message' => 'Rol eliminado correctamente']);
    }
}
