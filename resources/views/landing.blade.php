<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Alquiler - Encuentra tu hogar ideal</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navegación -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold">Sistema de Alquiler</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-md text-sm font-medium">
                        Registrarse
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sección Hero -->
    <section class="hero-section text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Encuentra tu lugar ideal para vivir</h1>
                <p class="text-xl mb-8">Los mejores apartamentos disponibles para ti. Confort, ubicación y precios accesibles.</p>
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-md text-lg font-medium inline-flex items-center">
                    Explorar Apartamentos
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Estadísticas -->
    <section class="py-8 bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">{{ $totalApartamentosDisponibles }}</div>
                    <div class="text-lg">Apartamentos Disponibles</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">{{ $totalEdificios }}</div>
                    <div class="text-lg">Edificios</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">100%</div>
                    <div class="text-lg">Satisfacción</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Beneficios -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">¿Por qué elegirnos?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="mx-auto bg-blue-100 rounded-full p-3 h-16 w-16 flex items-center justify-center mb-4">
                        <i class="fas fa-home text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Variedad de Opciones</h3>
                    <p class="text-gray-600">Contamos con apartamentos para todos los gustos y presupuestos.</p>
                </div>
                <div class="text-center p-6">
                    <div class="mx-auto bg-blue-100 rounded-full p-3 h-16 w-16 flex items-center justify-center mb-4">
                        <i class="fas fa-star text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Calidad Garantizada</h3>
                    <p class="text-gray-600">Todos nuestros apartamentos cumplen con altos estándares de calidad y mantenimiento.</p>
                </div>
                <div class="text-center p-6">
                    <div class="mx-auto bg-blue-100 rounded-full p-3 h-16 w-16 flex items-center justify-center mb-4">
                        <i class="fas fa-comments text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-800">Soporte Continuo</h3>
                    <p class="text-gray-600">Nuestro equipo está siempre disponible para resolver dudas, quejas o problemas de mantenimiento.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Apartamentos Destacados -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Apartamentos Destacados</h2>
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    Ver todos
                    <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($apartamentosDestacados as $apartamento)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-48 bg-gray-300 relative">
                            @if($apartamento->imagen)
                                <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Apartamento {{ $apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full bg-gray-200">
                                    <i class="fas fa-home text-5xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">Apartamento {{ $apartamento->numero_apartamento }}</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex items-center mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $apartamento->rating)
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
                                    {{ $apartamento->rating }} ({{ $apartamento->total_reviews }} valoraciones)
                                </span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-1"></i>
                                {{ $apartamento->edificio->nombre }}
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-blue-600">${{ number_format($apartamento->precio, 0) }}/mes</span>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Disponible</span>
                            </div>
                            <a href="{{ route('login') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-10">
                        <i class="fas fa-home text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600">No hay apartamentos disponibles en este momento</h3>
                        <p class="text-gray-500 mt-2">Por favor, vuelve a revisar más tarde</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">¿Listo para encontrar tu hogar ideal?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">Regístrate ahora y accede a todos nuestros apartamentos disponibles. El proceso es rápido y sencillo.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('login') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-md text-lg font-medium">
                    Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="bg-blue-800 text-white hover:bg-blue-700 px-6 py-3 rounded-md text-lg font-medium">
                    Registrarse
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <span class="text-xl font-bold">Sistema de Alquiler</span>
                    <p class="mt-2 text-gray-400 text-sm">La mejor plataforma para encontrar tu hogar ideal.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wider mb-3">Navegación</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Explorar Apartamentos</a></li>
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Iniciar Sesión</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Registrarse</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wider mb-3">Contacto</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                <span class="text-gray-400">info@sistemaalquiler.com</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                <span class="text-gray-400">(123) 456-7890</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 md:flex md:items-center md:justify-between">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} Sistema de Alquiler. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
