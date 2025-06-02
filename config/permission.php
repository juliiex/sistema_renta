<?php

return [
    'models' => [
        // Usa tus propios modelos
        'permission' => App\Models\Permiso::class,
        'role' => App\Models\Rol::class,
    ],

    'table_names' => [
        // Usa tus nombres de tablas
        'roles' => 'rol',  // tu tabla se llama 'rol' en singular
        'permissions' => 'permiso', // tu tabla se llama 'permiso' en singular
        'model_has_permissions' => 'model_has_permissions', // No la usamos, pero debe existir
        'model_has_roles' => 'usuarios_roles', // Tu tabla pivote usuario-rol
        'role_has_permissions' => 'roles_permisos', // Tu tabla pivote rol-permiso
    ],

    'column_names' => [
        'role_pivot_key' => 'rol_id', // Nombre de la columna rol_id en tus tablas pivote
        'permission_pivot_key' => 'permiso_id', // Nombre de la columna permiso_id en tus tablas pivote
        'model_morph_key' => 'usuario_id', // Nombre de la columna usuario_id en la tabla usuarios_roles
    ],

    'register_permission_check_method' => true,
    'teams' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];
