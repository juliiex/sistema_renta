<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Permiso;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolesPermisosSeeder extends Seeder
{
    public function run()
    {
        // Crear todos los permisos basados en las entidades
        $entidades = [
            'usuario',
            'apartamento',
            'edificio',
            'contrato',
            'estado_alquiler',
            'evaluacion',
            'solicitud_alquiler',
            'recordatorio_pago',
            'reporte_problema',
            'queja',
            'permiso',
            'rol'
        ];

        $acciones = ['ver', 'crear', 'editar', 'eliminar'];

        // Limpiar permisos existentes para evitar duplicados
        DB::table('roles_permisos')->delete();
        DB::table('usuarios_roles')->delete();
        DB::table('permiso')->delete();
        DB::table('rol')->delete();

        // Crear permisos para cada entidad y acción
        $todosLosPermisos = [];
        $permisosCreados = [];
        foreach ($entidades as $entidad) {
            foreach ($acciones as $accion) {
                $nombrePermiso = "{$accion}_{$entidad}";
                $permiso = new Permiso();
                $permiso->nombre = $nombrePermiso;
                $permiso->guard_name = 'web';
                $permiso->save();

                $todosLosPermisos[] = $nombrePermiso;
                $permisosCreados[$nombrePermiso] = $permiso->id; // Guardamos el ID para usarlo después

                $this->command->info("Permiso creado: {$nombrePermiso}");
            }
        }

        // Crear roles principales
        $roles = [
            'admin' => 'Administrador con acceso completo al sistema',
            'propietario' => 'Propietario de los inmuebles',
            'inquilino' => 'Usuario con contrato de alquiler',
            'posible inquilino' => 'Usuario registrado interesado en alquilar'
        ];

        $rolesCreados = [];
        foreach ($roles as $nombre => $descripcion) {
            $rol = new Rol();
            $rol->nombre = $nombre;
            $rol->guard_name = 'web';
            $rol->save();
            $rolesCreados[$nombre] = $rol->id; // Guardamos el ID para usarlo después
            $this->command->info("Rol creado: {$nombre}");
        }

        // Asignar permisos a roles
        // Admin: todos los permisos
        foreach ($permisosCreados as $nombrePermiso => $permisoId) {
            DB::table('roles_permisos')->insert([
                'rol_id' => $rolesCreados['admin'],
                'permiso_id' => $permisoId
            ]);
        }
        $this->command->info('Permisos asignados al rol de administrador');

        // Propietario: permisos específicos
        $permisosPropiertario = [
            // Apartamentos
            'ver_apartamento', 'crear_apartamento', 'editar_apartamento', 'eliminar_apartamento',
            // Edificios
            'ver_edificio', 'crear_edificio', 'editar_edificio', 'eliminar_edificio',
            // Contratos
            'ver_contrato', 'crear_contrato', 'editar_contrato',
            // Inquilinos (usuarios)
            'ver_usuario',
            // Solicitudes de alquiler
            'ver_solicitud_alquiler', 'editar_solicitud_alquiler',
            // Recordatorios de pago
            'ver_recordatorio_pago', 'crear_recordatorio_pago', 'editar_recordatorio_pago',
            // Reportes de problemas
            'ver_reporte_problema',
            // Quejas
            'ver_queja', 'editar_queja',
            // Estados de alquiler
            'ver_estado_alquiler', 'crear_estado_alquiler', 'editar_estado_alquiler',
            // Evaluaciones
            'ver_evaluacion'
        ];

        foreach ($permisosPropiertario as $nombrePermiso) {
            if (isset($permisosCreados[$nombrePermiso])) {
                DB::table('roles_permisos')->insert([
                    'rol_id' => $rolesCreados['propietario'],
                    'permiso_id' => $permisosCreados[$nombrePermiso]
                ]);
            }
        }
        $this->command->info('Permisos asignados al rol de propietario');

        // Inquilino: permisos específicos
        $permisosInquilino = [
            // Apartamentos
            'ver_apartamento',
            // Contratos
            'ver_contrato',
            // Reportes de problemas
            'ver_reporte_problema', 'crear_reporte_problema',
            // Quejas
            'ver_queja', 'crear_queja',
            // Evaluaciones
            'ver_evaluacion', 'crear_evaluacion',
            // Estados de alquiler
            'ver_estado_alquiler',
            // Recordatorios de pago
            'ver_recordatorio_pago'
        ];

        foreach ($permisosInquilino as $nombrePermiso) {
            if (isset($permisosCreados[$nombrePermiso])) {
                DB::table('roles_permisos')->insert([
                    'rol_id' => $rolesCreados['inquilino'],
                    'permiso_id' => $permisosCreados[$nombrePermiso]
                ]);
            }
        }
        $this->command->info('Permisos asignados al rol de inquilino');

        // Posible inquilino: permisos específicos
        $permisosPosibleInquilino = [
            // Apartamentos
            'ver_apartamento',
            // Solicitudes de alquiler
            'ver_solicitud_alquiler', 'crear_solicitud_alquiler',
            // Evaluaciones
            'ver_evaluacion'
        ];

        foreach ($permisosPosibleInquilino as $nombrePermiso) {
            if (isset($permisosCreados[$nombrePermiso])) {
                DB::table('roles_permisos')->insert([
                    'rol_id' => $rolesCreados['posible inquilino'],
                    'permiso_id' => $permisosCreados[$nombrePermiso]
                ]);
            }
        }
        $this->command->info('Permisos asignados al rol de posible inquilino');

        // Crear usuario administrador automáticamente
        $adminUser = Usuario::where('correo', 'admin@sistema.com')->first();
        if (!$adminUser) {
            $adminUser = Usuario::create([
                'nombre' => 'Administrador',
                'correo' => 'admin@sistema.com',
                'telefono' => '123456789',
                'contraseña' => Hash::make('admin123456'),
            ]);
            $this->command->info('Usuario administrador creado');
        }

        // Asignar rol admin al usuario
        DB::table('usuarios_roles')->insert([
            'usuario_id' => $adminUser->id,
            'rol_id' => $rolesCreados['admin']
        ]);
        $this->command->info('Rol de administrador asignado al usuario admin@sistema.com');

        // Crear usuario propietario automáticamente
        $propietarioUser = Usuario::where('correo', 'propietario@sistema.com')->first();
        if (!$propietarioUser) {
            $propietarioUser = Usuario::create([
                'nombre' => 'Propietario',
                'correo' => 'propietario@sistema.com',
                'telefono' => '987654321',
                'contraseña' => Hash::make('propietario123'),
            ]);
            $this->command->info('Usuario propietario creado');
        }

        // Asignar rol propietario al usuario
        DB::table('usuarios_roles')->insert([
            'usuario_id' => $propietarioUser->id,
            'rol_id' => $rolesCreados['propietario']
        ]);
        $this->command->info('Rol de propietario asignado al usuario propietario@sistema.com');

        // Verificar si hay un usuario juliiex y asignarle rol admin
        $juliiexUser = Usuario::where('correo', 'juliiex@test.com')
                            ->orWhere('nombre', 'juliiex')
                            ->first();

        if ($juliiexUser) {
            // Verificar si ya tiene el rol admin
            $tieneRolAdmin = DB::table('usuarios_roles')
                ->where('usuario_id', $juliiexUser->id)
                ->where('rol_id', $rolesCreados['admin'])
                ->exists();

            if (!$tieneRolAdmin) {
                DB::table('usuarios_roles')->insert([
                    'usuario_id' => $juliiexUser->id,
                    'rol_id' => $rolesCreados['admin']
                ]);
                $this->command->info("Rol de administrador asignado al usuario {$juliiexUser->nombre}");
            }
        }
    }
}
