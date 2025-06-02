<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Panel de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js para componentes interactivos -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navegación superior -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="text-xl font-bold">Sistema de Alquiler</div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded hidden sm:inline-block">
                        {{ $esInquilino ? 'Inquilino' : 'Usuario' }}
                    </span>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <span class="hidden md:block">{{ $usuario->nombre }}</span>
                            <img src="{{ $usuario->avatar ? asset('storage/avatars/' . $usuario->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($usuario->nombre) . '&color=7F9CF5&background=EBF4FF' }}"
                                alt="Avatar" class="h-8 w-8 rounded-full">
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="max-w-7xl mx-auto px-4 py-6 sm:py-8">
        <!-- Banner de bienvenida -->
        <div class="bg-white shadow-md rounded-lg p-4 sm:p-6 mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Bienvenido, {{ $usuario->nombre }}</h1>
            <p class="text-gray-600 mt-1">
                @if($esInquilino)
                    Accede a toda la información sobre tus contratos, reporta problemas y explora nuevas oportunidades de alquiler.
                @else
                    Explora nuestros apartamentos disponibles y envía solicitudes de alquiler.
                @endif
            </p>
        </div>

        <!-- Panel principal de contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Panel lateral con información específica - AHORA PRIMERO -->
            <div class="lg:col-span-1">
                <!-- Sección de acciones rápidas - AHORA PRIMERO -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                    <div class="bg-green-600 text-white px-4 py-3">
                        <h2 class="font-semibold text-lg">Acciones Rápidas</h2>
                    </div>
                    <div class="p-4">
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('usuario.apartamentos.explorar') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Explorar Apartamentos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('usuario.solicitudes.lista') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Mis Solicitudes
                                </a>
                            </li>
                            @if($esPosibleInquilino && $tieneContratosPendientesFirma)
                                <li>
                                    <a href="{{ route('usuario.firma.index') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        Firmar mis contratos
                                        <span class="ml-1 bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded">{{ $contratosPendientesFirma->count() }}</span>
                                    </a>
                                </li>
                            @endif
                            @if($esInquilino)
                                <li>
                                    <a href="{{ route('usuario.quejas.index') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                        </svg>
                                        Quejas de la Comunidad
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('usuario.quejas.mis-quejas') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Mis Quejas
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('usuario.quejas.crear') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        Presentar una Queja
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('usuario.reportes.nuevo') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Reportar un Problema
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('usuario.contratos.lista') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Ver Mis Contratos
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                @if($esInquilino)
                    <!-- Mis últimos reportes - Mantener esta sección -->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                        <div class="bg-red-600 text-white px-4 py-3">
                            <h2 class="font-semibold text-lg">Mis Últimos Reportes</h2>
                        </div>
                        <div class="p-4">
                            @if(count($misReportes) > 0)
                                <ul class="space-y-3">
                                    @foreach($misReportes as $reporte)
                                        <li class="border-b pb-2">
                                            <p class="font-medium">{{ \Illuminate\Support\Str::limit($reporte->descripcion, 30) }}</p>
                                            <div class="flex flex-wrap justify-between text-sm">
                                                <span class="text-gray-500">{{ $reporte->fecha_reporte->format('d/m/Y') }}</span>
                                                <span class="
                                                    @if($reporte->estado == 'pendiente') text-yellow-600
                                                    @elseif($reporte->estado == 'en_proceso') text-blue-600
                                                    @elseif($reporte->estado == 'resuelto') text-green-600
                                                    @else text-red-600 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $reporte->estado)) }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-3 text-center">
                                    <a href="{{ route('usuario.reportes.lista') }}" class="text-blue-500 hover:underline text-sm">Ver todos mis reportes</a>
                                </div>
                            @else
                                <p class="text-center text-gray-500 py-4">No has creado reportes de problemas.</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Mis solicitudes (visible para ambos roles) -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                    <div class="bg-yellow-600 text-white px-4 py-3">
                        <h2 class="font-semibold text-lg">Mis Solicitudes Recientes</h2>
                    </div>
                    <div class="p-4">
                        @if(count($misSolicitudes) > 0)
                            <ul class="space-y-3">
                                @foreach($misSolicitudes as $solicitud)
                                    <li class="border-b pb-2">
                                        <p class="font-medium">Apartamento {{ $solicitud->apartamento->numero_apartamento }}</p>
                                        <div class="flex flex-wrap justify-between text-sm">
                                            <span class="text-gray-500">{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</span>
                                            <span class="
                                                @if($solicitud->estado_solicitud == 'pendiente') text-yellow-600
                                                @elseif($solicitud->estado_solicitud == 'aprobada') text-green-600
                                                @else text-red-600 @endif">
                                                {{ ucfirst($solicitud->estado_solicitud) }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-3 text-center">
                                <a href="{{ route('usuario.solicitudes.lista') }}" class="text-blue-500 hover:underline text-sm">Ver todas mis solicitudes</a>
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-4">No has realizado solicitudes.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sección principal - AHORA DESPUÉS -->
            <div class="lg:col-span-2">
                <!-- Mi Apartamento o Mis Apartamentos (solo para inquilinos) -->
                @if($esInquilino && $misContratos->count() > 0)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                        <div class="bg-indigo-600 text-white px-4 py-3 flex justify-between items-center">
                            <h2 class="font-semibold text-lg">{{ $tieneMultiplesApartamentos ? 'Mis Apartamentos' : 'Mi Apartamento' }}</h2>
                            <span class="text-xs font-medium bg-indigo-800 text-white px-2 py-1 rounded-full">{{ $misContratos->count() }} {{ $tieneMultiplesApartamentos ? 'apartamentos' : 'apartamento' }}</span>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 {{ $tieneMultiplesApartamentos ? 'md:grid-cols-2' : '' }} gap-4">
                                @foreach($misContratos as $contrato)
                                    <div class="border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                                        <div class="bg-gray-200 h-48 relative">
                                            @if($contrato->apartamento->imagen)
                                                <img src="{{ asset('storage/' . $contrato->apartamento->imagen) }}" alt="Apartamento" class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full bg-gray-200">
                                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                                <h3 class="text-lg font-semibold text-white">Apartamento {{ $contrato->apartamento->numero_apartamento }}</h3>
                                                <p class="text-sm text-gray-200">{{ $contrato->apartamento->edificio->nombre }}</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-lg font-bold">${{ number_format($contrato->apartamento->precio, 0) }}/mes</span>
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $contrato->estado }}</span>
                                            </div>

                                            <!-- Calificación -->
                                            @php
                                                $promedio = $contrato->apartamento->evaluaciones->avg('calificacion');
                                                $total = $contrato->apartamento->evaluaciones->count();
                                                $rating = $promedio ? round($promedio, 1) : 0;
                                            @endphp
                                            <div class="flex items-center mb-3">
                                                <div class="flex items-center mr-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $rating)
                                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @elseif($i - 0.5 <= $rating)
                                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-600">
                                                    {{ $rating }} ({{ $total }} valoraciones)
                                                </span>
                                            </div>

                                            <!-- Info contrato -->
                                            <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                                                <div>
                                                    <span class="text-gray-600">Inicio: </span>
                                                    <span class="font-medium">{{ $contrato->fecha_inicio->format('d/m/Y') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">Fin: </span>
                                                    <span class="font-medium">{{ $contrato->fecha_fin->format('d/m/Y') }}</span>
                                                </div>
                                            </div>

                                            <!-- Botones de acción -->
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ route('usuario.mi-apartamento.detalle', $contrato->id) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-md transition duration-150">
                                                    Ver detalles
                                                </a>

                                                @if(!$contrato->ya_evaluado)
                                                    <button onclick="window.location.href='{{ route('usuario.mi-apartamento.detalle', $contrato->id) }}#evaluar'"
                                                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-4 rounded-md transition duration-150">
                                                        Evaluar apartamento
                                                    </button>
                                                @else
                                                    <div class="w-full bg-gray-100 text-gray-500 text-center py-2 px-4 rounded-md">
                                                        Ya has evaluado este apartamento
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Apartamentos anteriores (contratos inactivos) -->
                    @if($tieneApartamentosAnteriores)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-700 text-white px-4 py-3 flex justify-between items-center">
                                <h2 class="font-semibold text-lg">Apartamentos Anteriores</h2>
                                <span class="text-xs font-medium bg-gray-600 text-white px-2 py-1 rounded-full">{{ $contratosAnteriores->count() }} {{ $contratosAnteriores->count() > 1 ? 'contratos' : 'contrato' }}</span>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($contratosAnteriores as $contrato)
                                        <div class="border rounded-lg overflow-hidden shadow-sm bg-gray-50">
                                            <div class="p-4">
                                                <h3 class="font-semibold mb-2">Apartamento {{ $contrato->apartamento->numero_apartamento }}</h3>
                                                <p class="text-sm text-gray-600 mb-2">{{ $contrato->apartamento->edificio->nombre }}</p>

                                                <!-- Info contrato -->
                                                <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-600">Inicio: </span>
                                                        <span class="font-medium">{{ $contrato->fecha_inicio->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-600">Fin: </span>
                                                        <span class="font-medium">{{ $contrato->fecha_fin->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>

                                                @if(!$contrato->ya_evaluado)
                                                    <button onclick="window.location.href='{{ route('usuario.mi-apartamento.detalle', $contrato->id) }}#evaluar'"
                                                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-4 rounded-md transition duration-150">
                                                        Evaluar apartamento
                                                    </button>
                                                @else
                                                    <div class="text-center py-2 px-4">
                                                        <span class="text-sm text-gray-500">Tu evaluación: {{ $contrato->evaluacion->calificacion }}/5</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif($esInquilino)
                    <!-- El usuario es inquilino pero no tiene apartamentos -->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                        <div class="bg-indigo-600 text-white px-4 py-3">
                            <h2 class="font-semibold text-lg">Mi Apartamento</h2>
                        </div>
                        <div class="p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes apartamentos actualmente</h3>
                            <p class="text-gray-500 mb-6">Actualmente no tienes ningún contrato activo. Explora los apartamentos disponibles y realiza una solicitud.</p>
                            <a href="{{ route('usuario.apartamentos.explorar') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                Explorar apartamentos
                            </a>
                        </div>
                    </div>
                @endif

                <!-- NUEVA SECCIÓN: Contratos pendientes de firma (solo para posibles inquilinos) -->
                @if($esPosibleInquilino && $tieneContratosPendientesFirma)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                        <div class="bg-blue-600 text-white px-4 py-3 flex justify-between items-center">
                            <h2 class="font-semibold text-lg">Contratos Pendientes de Firma</h2>
                            <span class="text-xs font-medium bg-blue-800 text-white px-2 py-1 rounded-full">{{ $contratosPendientesFirma->count() }} {{ $contratosPendientesFirma->count() > 1 ? 'contratos' : 'contrato' }}</span>
                        </div>
                        <div class="p-4">
                            <!-- Mensaje de alerta -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm">
                                            Tienes {{ $contratosPendientesFirma->count() }} contrato(s) pendiente(s) de firma. Es necesario que los firmes para poder mudarte y convertirte en inquilino.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                @foreach($contratosPendientesFirma as $contrato)
                                    <div class="border rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                                        <div class="bg-gray-200 h-48 relative">
                                            @if($contrato->apartamento->imagen)
                                                <img src="{{ asset('storage/' . $contrato->apartamento->imagen) }}" alt="Apartamento" class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full bg-gray-200">
                                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                                <h3 class="text-lg font-semibold text-white">Apartamento {{ $contrato->apartamento->numero_apartamento }}</h3>
                                                <p class="text-sm text-gray-200">{{ $contrato->apartamento->edificio->nombre }}</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-lg font-bold">${{ number_format($contrato->apartamento->precio, 0) }}/mes</span>
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pendiente de firma</span>
                                            </div>

                                            <!-- Info contrato -->
                                            <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                                                <div>
                                                    <span class="text-gray-600">Inicio: </span>
                                                    <span class="font-medium">{{ $contrato->fecha_inicio->format('d/m/Y') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">Fin: </span>
                                                    <span class="font-medium">{{ $contrato->fecha_fin->format('d/m/Y') }}</span>
                                                </div>
                                            </div>

                                            <!-- Botón de firma -->
                                            <a href="{{ route('usuario.firma.firmar', $contrato->id) }}"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition duration-150 flex items-center justify-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                                Firmar contrato
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 text-center">
                                <a href="{{ route('usuario.firma.index') }}" class="text-blue-500 hover:underline flex items-center justify-center">
                                    Ver todos mis contratos pendientes
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Apartamentos disponibles (visible para ambos roles) -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                    <div class="bg-blue-600 text-white px-4 py-3">
                        <h2 class="font-semibold text-lg">Apartamentos Disponibles</h2>
                    </div>
                    <div class="p-4">
                        @if(count($apartamentosDisponibles) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($apartamentosDisponibles as $apartamento)
                                    <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                                        <div class="bg-gray-200 h-32 flex items-center justify-center">
                                            @if($apartamento->imagen)
                                                <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Apartamento" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="p-3">
                                            <h3 class="font-semibold">Apartamento {{ $apartamento->numero_apartamento }}</h3>
                                            <p class="text-sm text-gray-600">{{ $apartamento->edificio->nombre }}</p>

                                            <!-- Calificación -->
                                            @php
                                                $promedio = $apartamento->evaluaciones->avg('calificacion');
                                                $total = $apartamento->evaluaciones->count();
                                                $rating = $promedio ? round($promedio, 1) : 0;
                                            @endphp
                                            <div class="flex items-center mb-2 mt-2">
                                                <div class="flex items-center mr-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $rating)
                                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-gray-600">
                                                    {{ $rating }} ({{ $total }})
                                                </span>
                                            </div>

                                            <div class="mt-2 flex flex-wrap justify-between items-center gap-2">
                                                <span class="text-lg font-bold">${{ number_format($apartamento->precio, 0) }}/mes</span>
                                                <a href="{{ route('usuario.apartamentos.detalle', $apartamento->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Ver detalles</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('usuario.apartamentos.explorar') }}" class="text-blue-500 hover:underline">Ver todos los apartamentos disponibles</a>
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-6">No hay apartamentos disponibles en este momento.</p>
                        @endif
                    </div>
                </div>

                <!-- Sección combinada de quejas (solo para inquilinos) - ÚNICO BLOQUE DE QUEJAS -->
                @if($esInquilino)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                        <div class="bg-orange-600 text-white px-4 py-3 flex flex-wrap justify-between items-center gap-3">
                            <h2 class="font-semibold text-lg">Quejas</h2>
                            <a href="{{ route('usuario.quejas.crear') }}" class="bg-white text-orange-600 hover:bg-orange-100 px-3 py-1 rounded text-sm font-medium transition duration-150">
                                Nueva Queja
                            </a>
                        </div>
                        <div class="p-4">
                            <!-- Tabs para cambiar entre mis quejas y quejas de la comunidad -->
                            <div x-data="{ tab: 'community' }">
                                <div class="border-b border-gray-200 mb-4">
                                    <nav class="flex -mb-px space-x-4">
                                        <button @click="tab = 'community'" :class="{ 'border-orange-500 text-orange-600': tab === 'community', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'community' }" class="py-2 px-1 border-b-2 font-medium text-sm">
                                            Quejas de la Comunidad
                                        </button>
                                        <button @click="tab = 'my'" :class="{ 'border-orange-500 text-orange-600': tab === 'my', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'my' }" class="py-2 px-1 border-b-2 font-medium text-sm">
                                            Mis Quejas
                                        </button>
                                    </nav>
                                </div>

                                <!-- Contenido de quejas de la comunidad -->
                                <div x-show="tab === 'community'">
                                    @if(count($quejasRecientes) > 0)
                                        <ul class="divide-y divide-gray-200">
                                            @foreach($quejasRecientes as $queja)
                                                <li class="py-3 hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('usuario.quejas.detalle', $queja->id) }}'">
                                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between">
                                                        <div class="flex-grow mb-2 sm:mb-0">
                                                            <p class="font-medium text-gray-800">{{ \Illuminate\Support\Str::limit($queja->descripcion, 100) }}</p>
                                                            <div class="flex flex-wrap items-center mt-1 gap-2">
                                                                <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-0.5 rounded">{{ $queja->tipo }}</span>
                                                                <span class="text-gray-500 text-xs">por {{ $queja->usuario->nombre }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="sm:ml-4 text-left sm:text-right">
                                                            <p class="text-sm text-gray-500">{{ $queja->fecha_envio->format('d/m/Y') }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('usuario.quejas.index') }}" class="text-blue-500 hover:underline text-sm">Ver todas las quejas</a>
                                        </div>
                                    @else
                                        <p class="text-center text-gray-500 py-4">No hay quejas recientes en la comunidad.</p>
                                    @endif
                                </div>

                                <!-- Contenido de mis quejas -->
                                <div x-show="tab === 'my'">
                                    @if(count($misQuejas) > 0)
                                        <ul class="divide-y divide-gray-200">
                                            @foreach($misQuejas as $queja)
                                                <li class="py-3 hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('usuario.quejas.detalle', $queja->id) }}'">
                                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between">
                                                        <div class="flex-grow mb-2 sm:mb-0">
                                                            <p class="font-medium text-gray-800">{{ \Illuminate\Support\Str::limit($queja->descripcion, 100) }}</p>
                                                            <div class="flex flex-wrap items-center mt-1 gap-2">
                                                                <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-0.5 rounded">{{ $queja->tipo }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="sm:ml-4 text-left sm:text-right">
                                                            <p class="text-sm text-gray-500">{{ $queja->fecha_envio->format('d/m/Y') }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('usuario.quejas.mis-quejas') }}" class="text-blue-500 hover:underline text-sm">Ver todas mis quejas</a>
                                        </div>
                                    @else
                                        <p class="text-center text-gray-500 py-4">No has presentado quejas aún.</p>
                                        <div class="flex justify-center">
                                            <a href="{{ route('usuario.quejas.crear') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-sm font-medium">
                                                Presentar mi primera queja
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
