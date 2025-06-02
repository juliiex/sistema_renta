<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', config('app.name'))</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }

        /* Estilos para scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navegación superior -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold">Sistema de Alquiler</span>
                    </a>

                    <!-- Menú desktop -->
                    <div class="hidden md:ml-6 md:flex md:items-center md:space-x-4">
                        @auth
                            @if(auth()->user()->hasRole(['admin', 'propietario']))
                                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700">Dashboard</a>
                            @else
                                <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700">Inicio</a>
                            @endif

                            <a href="{{ route('usuario.apartamentos.explorar') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700">Apartamentos</a>

                            @if(auth()->user()->hasRole('inquilino'))
                                <a href="{{ route('usuario.quejas.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700">Quejas</a>
                                <a href="{{ route('usuario.reportes.lista') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700">Reportes</a>
                                <a href="{{ route('usuario.contratos.lista') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-gray-700">Contratos</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Menú usuario y móvil -->
                <div class="flex items-center">
                    @auth
                        <div class="hidden md:flex md:items-center">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">
                                @if(auth()->user()->hasRole('admin'))
                                    Administrador
                                @elseif(auth()->user()->hasRole('propietario'))
                                    Propietario
                                @elseif(auth()->user()->hasRole('inquilino'))
                                    Inquilino
                                @else
                                    Usuario
                                @endif
                            </span>
                        </div>

                        <!-- Menú desplegable usuario -->
                        <div x-data="{ open: false }" class="ml-3 relative">
                            <div>
                                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Abrir menú usuario</span>
                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-white"
                                         src="{{ auth()->user()->avatar ? asset('storage/avatars/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nombre) . '&color=7F9CF5&background=EBF4FF' }}"
                                         alt="{{ auth()->user()->nombre }}">
                                </button>
                            </div>

                            <!-- Menú desplegable (invisible por defecto) -->
                            <div x-cloak x-show="open" @click.away="open = false"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <div class="px-4 py-2 border-b">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->nombre }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->correo }}</p>
                                </div>

                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mi Perfil</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <!-- Botón menú móvil -->
                    <div class="-mr-2 flex md:hidden">
                        <button x-data="{ open: false }" @click="open = !open; $dispatch('toggle-mobile-menu', {open})" type="button"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Abrir menú principal</span>
                            <!-- Icono menú -->
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menú móvil -->
        <div x-data="{ open: false }"
             x-on:toggle-mobile-menu.window="open = $event.detail.open"
             x-show="open"
             x-cloak
             class="md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @auth
                    @if(auth()->user()->hasRole(['admin', 'propietario']))
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Dashboard</a>
                    @else
                        <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Inicio</a>
                    @endif

                    <a href="{{ route('usuario.apartamentos.explorar') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Apartamentos</a>
                    <a href="{{ route('usuario.solicitudes.lista') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Solicitudes</a>

                    @if(auth()->user()->hasRole('inquilino'))
                        <a href="{{ route('usuario.quejas.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Quejas</a>
                        <a href="{{ route('usuario.quejas.mis-quejas') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 pl-6">- Mis Quejas</a>
                        <a href="{{ route('usuario.quejas.crear') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700 pl-6">- Nueva Queja</a>

                        <a href="{{ route('usuario.reportes.lista') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Reportes</a>
                        <a href="{{ route('usuario.contratos.lista') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Contratos</a>
                    @endif

                    <div class="pt-4 pb-3 border-t border-gray-700">
                        <div class="flex items-center px-5">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full"
                                     src="{{ auth()->user()->avatar ? asset('storage/avatars/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nombre) . '&color=7F9CF5&background=EBF4FF' }}"
                                     alt="{{ auth()->user()->nombre }}">
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium leading-none text-white">{{ auth()->user()->nombre }}</div>
                                <div class="text-sm font-medium leading-none text-gray-400 mt-1">{{ auth()->user()->correo }}</div>
                            </div>
                            <span class="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                @if(auth()->user()->hasRole('admin'))
                                    Administrador
                                @elseif(auth()->user()->hasRole('propietario'))
                                    Propietario
                                @elseif(auth()->user()->hasRole('inquilino'))
                                    Inquilino
                                @else
                                    Usuario
                                @endif
                            </span>
                        </div>
                        <div class="mt-3 px-2 space-y-1">
                            <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Mi Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-700">Registrarse</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="flex-grow">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 relative" role="alert">
                <p class="font-bold">Éxito</p>
                <p>{{ session('success') }}</p>
                <button @click="show = false" class="absolute top-0 right-0 mt-4 mr-4 text-green-700 hover:text-green-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
                <button @click="show = false" class="absolute top-0 right-0 mt-4 mr-4 text-red-700 hover:text-red-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 relative" role="alert">
                <p class="font-bold">Advertencia</p>
                <p>{{ session('warning') }}</p>
                <button @click="show = false" class="absolute top-0 right-0 mt-4 mr-4 text-yellow-700 hover:text-yellow-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Pie de página -->
    <footer class="bg-gray-800 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:order-2 space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Facebook</span>
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Instagram</span>
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Twitter</span>
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base text-gray-400">&copy; {{ date('Y') }} Sistema de Alquiler. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Cualquier script adicional que necesites
    </script>
    @stack('scripts')
</body>
</html>
