<?php
// Este es un archivo de diagnóstico temporal - ELIMINAR DESPUÉS DE RESOLVER EL PROBLEMA

// Cargar el entorno Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "<h1>Diagnóstico de Autenticación</h1>";

// 1. Verificar conexión a la base de datos
echo "<h2>1. Verificando conexión a la base de datos</h2>";
try {
    DB::connection()->getPdo();
    echo "<p style='color:green'>✓ Conexión a la base de datos OK</p>";
} catch (\Exception $e) {
    echo "<p style='color:red'>✗ Error de conexión: " . $e->getMessage() . "</p>";
}

// 2. Verificar tabla de usuarios
echo "<h2>2. Verificando estructura de la tabla de usuarios</h2>";
try {
    $columns = DB::getSchemaBuilder()->getColumnListing('usuario');
    echo "<p>Columnas en la tabla usuario:</p><ul>";
    foreach ($columns as $column) {
        echo "<li>$column</li>";
    }
    echo "</ul>";
} catch (\Exception $e) {
    echo "<p style='color:red'>✗ Error al consultar estructura: " . $e->getMessage() . "</p>";
}

// 3. Verificar usuarios en la base de datos
echo "<h2>3. Verificando usuarios en la base de datos</h2>";
try {
    $users = DB::table('usuario')->get();
    echo "<p>Total de usuarios: " . count($users) . "</p>";
    echo "<table border='1'><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Contraseña (hash)</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user->id . "</td>";
        echo "<td>" . $user->nombre . "</td>";
        echo "<td>" . $user->correo . "</td>";
        echo "<td>" . substr($user->contraseña, 0, 20) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (\Exception $e) {
    echo "<p style='color:red'>✗ Error al consultar usuarios: " . $e->getMessage() . "</p>";
}

// 4. Probar autenticación manual
echo "<h2>4. Probando autenticación manual</h2>";

// Formulario para probar login
echo "<form method='post'>";
echo "<input type='text' name='test_email' placeholder='Correo electrónico' value='admin@sistema.com'><br>";
echo "<input type='password' name='test_password' placeholder='Contraseña' value=''><br>";
echo "<button type='submit'>Probar Login</button>";
echo "</form>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email'])) {
    $email = $_POST['test_email'];
    $password = $_POST['test_password'];

    echo "<p>Intentando login con: $email</p>";

    // Buscar usuario por correo
    $user = DB::table('usuario')->where('correo', $email)->first();

    if (!$user) {
        echo "<p style='color:red'>✗ Usuario no encontrado con este correo</p>";
    } else {
        echo "<p>Usuario encontrado: " . $user->nombre . " (ID: " . $user->id . ")</p>";

        // Verificar contraseña manualmente
        $passwordMatches = Hash::check($password, $user->contraseña);
        echo "<p>" . ($passwordMatches
            ? "<span style='color:green'>✓ La contraseña coincide</span>"
            : "<span style='color:red'>✗ La contraseña NO coincide</span>") . "</p>";

        // Intentar login con Auth
        if (Auth::attempt(['correo' => $email, 'password' => $password])) {
            echo "<p style='color:green'>✓ Auth::attempt exitoso</p>";
        } else {
            echo "<p style='color:red'>✗ Auth::attempt fallido</p>";
        }

        // Probar login directo
        try {
            // Cargar el modelo Usuario
            $userModel = app('App\Models\Usuario')::find($user->id);
            if ($userModel) {
                Auth::login($userModel);
                echo "<p style='color:green'>✓ Login manual exitoso con ID " . Auth::id() . "</p>";
                echo "<p>Usuario autenticado: " . Auth::user()->nombre . "</p>";
            } else {
                echo "<p style='color:red'>✗ No se pudo cargar el modelo de usuario</p>";
            }
        } catch (\Exception $e) {
            echo "<p style='color:red'>✗ Error en login manual: " . $e->getMessage() . "</p>";
        }
    }
}

// 5. Verificar configuración de Auth
echo "<h2>5. Verificando configuración de autenticación</h2>";
try {
    $config = config('auth');
    echo "<pre>";
    print_r($config);
    echo "</pre>";
} catch (\Exception $e) {
    echo "<p style='color:red'>✗ Error al consultar configuración: " . $e->getMessage() . "</p>";
}

// 6. Mostrar logs recientes
echo "<h2>6. Últimas entradas del log</h2>";
try {
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        $logs = shell_exec("tail -n 50 " . escapeshellarg($logPath));
        echo "<pre>" . htmlspecialchars($logs) . "</pre>";
    } else {
        echo "<p>Archivo de logs no encontrado en $logPath</p>";
    }
} catch (\Exception $e) {
    echo "<p style='color:red'>✗ Error al leer logs: " . $e->getMessage() . "</p>";
}
?>
