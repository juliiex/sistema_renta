<?php

namespace App\Http\Controllers;

use App\Models\Queja;
use Illuminate\Http\Request;

class QuejaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/queja",
     *     summary="Obtener todas las quejas",
     *     tags={"Queja"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de quejas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Queja"))
     *     )
     * )
     */
    public function index()
    {
        $quejas = Queja::with('usuario')->get();
        return response()->json($quejas);
    }

    /**
     * @OA\Get(
     *     path="/api/queja/{id}",
     *     summary="Mostrar una queja específica",
     *     tags={"Queja"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la queja",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detalle de la queja", @OA\JsonContent(ref="#/components/schemas/Queja")),
     *     @OA\Response(response=404, description="Queja no encontrada")
     * )
     */
    public function show($id)
    {
        $queja = Queja::find($id);
        if ($queja) {
            return response()->json($queja);
        }
        return response()->json(['message' => 'Queja no encontrada'], 404);
    }

    /**
     * @OA\Get(
     *     path="/api/queja/create",
     *     summary="Formulario de creación de quejas",
     *     tags={"Queja"},
     *     @OA\Response(
     *         response=200,
     *         description="Formulario de creación"
     *     )
     * )
     */
    public function create()
    {
        return response()->json(['message' => 'Formulario de creación de quejas']);
    }

    /**
     * @OA\Post(
     *     path="/api/queja",
     *     summary="Crear una nueva queja",
     *     tags={"Queja"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"usuario_id", "descripcion", "tipo", "fecha_envio"},
     *             @OA\Property(property="usuario_id", type="integer", example=1),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la queja"),
     *             @OA\Property(property="tipo", type="string", example="General"),
     *             @OA\Property(property="fecha_envio", type="string", format="date", example="2025-04-08")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Queja creada"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuario,id',
            'descripcion' => 'required|string',
            'tipo' => 'required|string',
            'fecha_envio' => 'required|date',
        ]);

        $queja = Queja::create($request->all());
        return response()->json($queja, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/queja/{id}",
     *     summary="Actualizar una queja",
     *     tags={"Queja"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la queja a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="usuario_id", type="integer", example=1),
     *             @OA\Property(property="descripcion", type="string", example="Nueva descripción"),
     *             @OA\Property(property="tipo", type="string", example="Aplicativo"),
     *             @OA\Property(property="fecha_envio", type="string", format="date", example="2025-04-10")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Queja actualizada"),
     *     @OA\Response(response=404, description="Queja no encontrada")
     * )
     */
    public function update(Request $request, $id)
    {
        $queja = Queja::find($id);
        if ($queja) {
            $request->validate([
                'usuario_id' => 'required|exists:usuario,id',
                'descripcion' => 'required|string',
                'tipo' => 'required|string',
                'fecha_envio' => 'required|date',
            ]);

            $queja->update($request->all());
            return response()->json($queja);
        }
        return response()->json(['message' => 'Queja no encontrada'], 404);
    }

     /**
     * @OA\Delete(
     *     path="/api/queja/{id}",
     *     summary="Eliminar una queja",
     *     tags={"Queja"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la queja a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Queja eliminada correctamente"),
     *     @OA\Response(response=404, description="Queja no encontrada")
     * )
     */
    public function destroy($id)
    {
        $queja = Queja::find($id);
        if ($queja) {
            $queja->delete();
            return response()->json(['message' => 'Queja eliminada correctamente']);
        }
        return response()->json(['message' => 'Queja no encontrada'], 404);
    }
}
