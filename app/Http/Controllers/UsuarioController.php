<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Mostrar la lista de usuarios.
     */
    public function index()
    {
        // Obtener todos los usuarios
        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Crear un nuevo usuario.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo',
            'telefono' => 'required|string|max:20',
            'contraseña' => 'required|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para el avatar
        ]);

        // Manejo del avatar (si se ha subido uno)
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validatedData['avatar'] = $avatarPath;
        }

        // Crear el nuevo usuario
        $usuario = Usuario::create($validatedData);

        // Redirigir a la lista de usuarios con un mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Mostrar los detalles de un usuario específico.
     */
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Mostrar el formulario para editar un usuario.
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar los datos de un usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // Validación de los datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo,' . $usuario->id,
            'telefono' => 'required|string|max:20',
            'contraseña' => 'nullable|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para el avatar
        ]);

        // Manejo del avatar (si se ha subido un archivo nuevo)
        if ($request->hasFile('avatar')) {
            // Eliminar el avatar anterior si existe
            if ($usuario->avatar && file_exists(storage_path('app/public/' . $usuario->avatar))) {
                unlink(storage_path('app/public/' . $usuario->avatar));
            }

            // Subir el nuevo avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validatedData['avatar'] = $avatarPath;
        }

        // Actualizar los datos del usuario
        $usuario->update($validatedData);

        // Redirigir a la lista de usuarios con mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Eliminar el avatar si existe
        if ($usuario->avatar && file_exists(storage_path('app/public/' . $usuario->avatar))) {
            unlink(storage_path('app/public/' . $usuario->avatar));
        }

        // Eliminar el usuario
        $usuario->delete();

        // Redirigir a la lista de usuarios con mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }
}
