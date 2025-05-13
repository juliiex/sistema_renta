<?php
namespace App\Http\Controllers;

use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/permisos",
     *     summary="Listar todos los permisos",
     *     tags={"Permisos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de permisos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Permiso"))
     *     )
     * )
     */
    public function index()
    {
        $permisos = Permiso::all();
        return response()->json($permisos);
    }

    /**
     * @OA\Get(
     *     path="/api/permisos/create",
     *     summary="Mostrar formulario de creación de permiso",
     *     tags={"Permisos"},
     *     @OA\Response(
     *         response=200,
     *         description="Formulario de creación"
     *     )
     * )
     */
    public function create()
    {
        return response()->json(['message' => 'Formulario de creación de permisos']);
    }

    /**
     * @OA\Post(
     *     path="/api/permisos",
     *     summary="Crear un nuevo permiso",
     *     tags={"Permisos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Permiso")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permiso creado",
     *         @OA\JsonContent(ref="#/components/schemas/Permiso")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:permiso',
        ]);

        $permiso = Permiso::create($request->all());
        return response()->json($permiso, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/permisos/{id}",
     *     summary="Obtener un permiso por ID",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Permiso")
     *     )
     * )
     */
    public function show($id)
    {
        $permiso = Permiso::findOrFail($id);  // Esto buscará el permiso por ID
        return response()->json($permiso);
    }

    /**
     * @OA\Get(
     *     path="/api/permisos/{id}/edit",
     *     summary="Mostrar datos de un permiso para editar",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del permiso",
     *         @OA\JsonContent(ref="#/components/schemas/Permiso")
     *     )
     * )
     */
    public function edit($id)
    {
        $permiso = Permiso::findOrFail($id);  // Esto buscará el permiso por ID
        return response()->json($permiso);
    }

    /**
     * @OA\Put(
     *     path="/api/permisos/{id}",
     *     summary="Actualizar un permiso",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Permiso")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Permiso")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);  // Esto buscará el permiso por ID

        $request->validate([
            'nombre' => 'required|string|max:255|unique:permiso,nombre,' . $permiso->id,
        ]);

        $permiso->update($request->all());
        return response()->json($permiso);
    }

    /**
     * @OA\Delete(
     *     path="/api/permisos/{id}",
     *     summary="Eliminar un permiso",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso eliminado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);  // Esto buscará el permiso por ID
        $permiso->delete();

        return response()->json(['message' => 'Permiso eliminado']);
    }
}
