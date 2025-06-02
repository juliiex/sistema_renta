<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Constructor: añade middleware de permisos
     */
    public function __construct()
    {
        $this->middleware('permission:ver_usuario')->only(['index', 'show']);
        $this->middleware('permission:crear_usuario')->only(['create', 'store']);
        $this->middleware('permission:editar_usuario')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_usuario')->only(['destroy']);
    }

    /**
     * Mostrar la lista de usuarios.
     */
    public function index()
    {
        // Admin ve todos los usuarios
        if (auth()->user()->hasRole('admin')) {
            $usuarios = Usuario::all();
        }
        // Propietario ve inquilinos y posibles inquilinos
        elseif (auth()->user()->hasRole('propietario')) {
            $usuarios = Usuario::whereHas('roles', function($query) {
                $query->whereIn('nombre', ['inquilino', 'posible_inquilino']);
            })->get();
        }
        // El resto solo se ve a sí mismo
        else {
            $usuarios = Usuario::where('id', auth()->id())->get();
        }

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        // Obtener los roles disponibles según el usuario actual
        if (auth()->user()->hasRole('admin')) {
            // Admin puede asignar cualquier rol
            $roles = Rol::all();
        } elseif (auth()->user()->hasRole('propietario')) {
            // Propietario solo puede crear inquilinos o posibles inquilinos
            $roles = Rol::whereIn('nombre', ['inquilino', 'posible_inquilino'])->get();
        } else {
            // Otros usuarios no deberían poder crear usuarios, pero por si acaso
            $roles = collect();
        }

        return view('admin.usuarios.create', compact('roles'));
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|array',
            'roles.*' => 'exists:rol,id',
        ]);

        // Verificar que el usuario tenga permiso para asignar los roles seleccionados
        if (!auth()->user()->hasRole('admin')) {
            // Para propietario, verificar que solo asigne roles permitidos
            if (auth()->user()->hasRole('propietario')) {
                $rolesPermitidos = Rol::whereIn('nombre', ['inquilino', 'posible_inquilino'])
                    ->pluck('id')
                    ->toArray();

                foreach ($request->roles as $rolId) {
                    if (!in_array($rolId, $rolesPermitidos)) {
                        return back()->withErrors(['roles' => 'No tiene permiso para asignar algunos de los roles seleccionados.'])
                            ->withInput();
                    }
                }
            } else {
                // Otros usuarios no pueden asignar roles
                return back()->withErrors(['roles' => 'No tiene permiso para asignar roles.'])
                    ->withInput();
            }
        }

        // Manejo del avatar (si se ha subido uno)
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validatedData['avatar'] = $avatarPath;
        }

        // Hashear la contraseña
        $validatedData['contraseña'] = Hash::make($validatedData['contraseña']);

        // Crear el nuevo usuario
        $usuario = Usuario::create([
            'nombre' => $validatedData['nombre'],
            'correo' => $validatedData['correo'],
            'telefono' => $validatedData['telefono'],
            'contraseña' => $validatedData['contraseña'],
            'avatar' => $validatedData['avatar'] ?? null,
        ]);

        // Asignar roles
        $usuario->roles()->sync($request->roles);

        // Redirigir a la lista de usuarios con un mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Mostrar los detalles de un usuario específico.
     */
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Verificar permiso para ver este usuario
        if (!auth()->user()->hasRole('admin') &&
            !auth()->user()->hasRole('propietario') &&
            auth()->id() != $id) {
            abort(403, 'No tiene permiso para ver este usuario.');
        }

        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Mostrar el formulario para editar un usuario.
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Verificar permisos para editar
        if (!auth()->user()->hasRole('admin') &&
            !auth()->user()->hasRole('propietario') &&
            auth()->id() != $id) {
            abort(403, 'No tiene permiso para editar este usuario.');
        }

        // Obtener los roles disponibles según el usuario actual
        if (auth()->user()->hasRole('admin')) {
            // Admin puede asignar cualquier rol
            $roles = Rol::all();
        } elseif (auth()->user()->hasRole('propietario')) {
            // Propietario solo puede asignar inquilino o posible inquilino
            $roles = Rol::whereIn('nombre', ['inquilino', 'posible_inquilino'])->get();
        } else {
            // Usuarios normales no pueden cambiar roles
            $roles = collect();
        }

        // Obtener los roles actuales del usuario
        $usuarioRoles = $usuario->roles()->pluck('rol.id')->toArray();

        return view('admin.usuarios.edit', compact('usuario', 'roles', 'usuarioRoles'));
    }

    /**
     * Actualizar los datos de un usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // Verificar permisos para actualizar
        if (!auth()->user()->hasRole('admin') &&
            !auth()->user()->hasRole('propietario') &&
            auth()->id() != $id) {
            abort(403, 'No tiene permiso para actualizar este usuario.');
        }

        // Validación básica
        $rules = [
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo,' . $usuario->id,
            'telefono' => 'required|string|max:20',
            'contraseña' => 'nullable|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Validación de roles solo para admin y propietario
        if (auth()->user()->hasRole(['admin', 'propietario'])) {
            $rules['roles'] = 'required|array';
            $rules['roles.*'] = 'exists:rol,id';
        }

        $validatedData = $request->validate($rules);

        // Si es admin o propietario y está cambiando roles
        if ((auth()->user()->hasRole('admin') || auth()->user()->hasRole('propietario')) &&
            isset($request->roles)) {

            // Verificar que el usuario tenga permiso para asignar los roles seleccionados
            if (!auth()->user()->hasRole('admin')) {
                // Para propietario, verificar que solo asigne roles permitidos
                $rolesPermitidos = Rol::whereIn('nombre', ['inquilino', 'posible_inquilino'])
                    ->pluck('id')
                    ->toArray();

                foreach ($request->roles as $rolId) {
                    if (!in_array($rolId, $rolesPermitidos)) {
                        return back()->withErrors(['roles' => 'No tiene permiso para asignar algunos de los roles seleccionados.'])
                            ->withInput();
                    }
                }

                // No permitir que propietario modifique roles de admin u otros propietarios
                if ($usuario->hasRole(['admin', 'propietario']) && !auth()->user()->hasRole('admin')) {
                    return back()->withErrors(['roles' => 'No tiene permiso para modificar los roles de este usuario.'])
                        ->withInput();
                }
            }

            // Actualizar roles
            $usuario->roles()->sync($request->roles);
        }

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

        // Actualizar los campos básicos
        $usuario->nombre = $validatedData['nombre'];
        $usuario->correo = $validatedData['correo'];
        $usuario->telefono = $validatedData['telefono'];

        // Solo actualizar la contraseña si viene llena (y cifrarla)
        if (!empty($validatedData['contraseña'])) {
            $usuario->contraseña = Hash::make($validatedData['contraseña']);
        }

        // Actualizar avatar si se subió uno
        if (isset($validatedData['avatar'])) {
            $usuario->avatar = $validatedData['avatar'];
        }

        $usuario->save();

        // Redirigir a la lista de usuarios con mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Verificar permisos especiales para eliminar
        if (!auth()->user()->hasRole('admin')) {
            if (auth()->user()->hasRole('propietario')) {
                // Propietario no puede eliminar admin u otros propietarios
                if ($usuario->hasRole(['admin', 'propietario'])) {
                    return redirect()->route('usuarios.index')
                        ->with('error', 'No tiene permiso para eliminar este tipo de usuario.');
                }
            } else {
                // Usuarios normales solo pueden eliminarse a sí mismos
                if (auth()->id() != $id) {
                    return redirect()->route('usuarios.index')
                        ->with('error', 'No tiene permiso para eliminar a otro usuario.');
                }
            }
        }

        // No permitir eliminar el último administrador del sistema
        if ($usuario->hasRole('admin')) {
            $adminCount = Usuario::whereHas('roles', function($query) {
                $query->where('nombre', 'admin');
            })->count();

            if ($adminCount <= 1) {
                return redirect()->route('usuarios.index')
                    ->with('error', 'No se puede eliminar el último administrador del sistema.');
            }
        }

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
