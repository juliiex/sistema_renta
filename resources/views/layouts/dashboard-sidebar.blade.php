@extends('layouts.app')

@section('title', isset($pageTitle) ? $pageTitle : 'Dashboard')

@push('styles')
<style>
    /* Estilos para el sidebar */
    #sidebar {
        transition: transform 0.3s ease-in-out;
        width: 16rem;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 40;
        background-color: #1f2937;
        overflow-y: auto; /* Añadido para permitir scroll vertical */
        /* Personalización del scrollbar para navegadores webkit (Chrome, Safari, Edge) */
        scrollbar-width: thin; /* Para Firefox */
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent; /* Para Firefox */
    }

    /* Personalización del scrollbar para Chrome, Safari y Edge */
    #sidebar::-webkit-scrollbar {
        width: 4px; /* Ancho más delgado */
    }

    #sidebar::-webkit-scrollbar-track {
        background: transparent; /* Fondo transparente */
    }

    #sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2); /* Color semi-transparente */
        border-radius: 4px;
    }

    #sidebar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(255, 255, 255, 0.3); /* Un poco más visible al hover */
    }

    /* Estilos para la navegación dentro del sidebar */
    #sidebar nav {
        display: flex;
        flex-direction: column;
        height: calc(100% - 130px); /* Altura restante después del header */
        overflow-y: auto; /* Scroll para el contenido de navegación */
    }

    /* Personalización del scrollbar del nav para Chrome, Safari y Edge */
    #sidebar nav::-webkit-scrollbar {
        width: 4px; /* Ancho más delgado */
    }

    #sidebar nav::-webkit-scrollbar-track {
        background: transparent; /* Fondo transparente */
    }

    #sidebar nav::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2); /* Color semi-transparente */
        border-radius: 4px;
    }

    #sidebar nav::-webkit-scrollbar-thumb:hover {
        background-color: rgba(255, 255, 255, 0.3); /* Un poco más visible al hover */
    }

    #sidebar.collapsed {
        transform: translateX(-100%);
    }

    #content {
        transition: margin-left 0.3s ease-in-out;
        margin-left: 16rem;
    }

    #content.expanded {
        margin-left: 0;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #e5e7eb;
        transition: background-color 0.2s;
    }

    .sidebar-item:hover, .sidebar-item.active {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-item i {
        margin-right: 0.75rem;
        width: 1.25rem;
        text-align: center;
    }

    .sidebar-section {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-section-title {
        color: #9ca3af;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0 1.25rem;
        margin-bottom: 0.5rem;
    }

    /* Estilos específicos para el dashboard */
    .dashboard-card {
        transition: transform 0.2s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
    }

    .chart-container {
        height: 250px;
    }

    /* Logout en la parte inferior */
    .sidebar-footer {
        position: sticky;
        bottom: 0;
        width: 100%;
        padding: 1rem;
        background-color: #1f2937;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Media queries para responsividad */
    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%);
        }

        #sidebar.mobile-visible {
            transform: translateX(0);
        }

        #content {
            margin-left: 0;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 30;
            display: none;
        }

        .overlay.visible {
            display: block;
        }
    }
</style>
<!-- Chart.js para los gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<!-- Overlay para móviles -->
<div id="overlay" class="overlay"></div>

<!-- Sidebar -->
<div id="sidebar">
    <!-- Logo y título -->
    <div class="p-4 bg-gray-900">
        <h2 class="text-xl font-bold text-white">Sistema de Alquiler</h2>
        <p class="text-sm text-gray-400">{{ auth()->user()->hasRole('admin') ? 'Administrador' : 'Propietario' }}</p>
    </div>

    <nav class="mt-4">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active bg-gray-700' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        @if(auth()->user()->hasRole('admin'))
            <!-- Sección de Administración del Sistema (solo para admin) -->
            <div class="sidebar-section">
                <h3 class="sidebar-section-title">Administración</h3>
                <a href="{{ route('usuarios.index') }}" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                <a href="{{ route('rol.index') }}" class="sidebar-item">
                    <i class="fas fa-user-shield"></i>
                    <span>Roles</span>
                </a>
                <a href="{{ route('permiso.index') }}" class="sidebar-item">
                    <i class="fas fa-key"></i>
                    <span>Permisos</span>
                </a>
                <a href="{{ route('rol_permiso.index') }}" class="sidebar-item">
                    <i class="fas fa-lock"></i>
                    <span>Roles y Permisos</span>
                </a>
                <a href="{{ route('usuario_rol.index') }}" class="sidebar-item">
                    <i class="fas fa-user-tag"></i>
                    <span>Usuarios y Roles</span>
                </a>
            </div>
        @endif

        <!-- Sección de Propiedades -->
        <div class="sidebar-section">
            <h3 class="sidebar-section-title">Propiedades</h3>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('edificio.index') }}" class="sidebar-item">
                    <i class="fas fa-building"></i>
                    <span>Edificios</span>
                </a>
                <a href="{{ route('apartamento.index') }}" class="sidebar-item">
                    <i class="fas fa-home"></i>
                    <span>Apartamentos</span>
                </a>
                <a href="{{ route('evaluaciones.index') }}" class="sidebar-item">
                    <i class="fas fa-star"></i>
                    <span>Evaluaciones</span>
                </a>
            @else
                <a href="{{ route('propietario.edificios.index') }}" class="sidebar-item">
                    <i class="fas fa-building"></i>
                    <span>Mis Edificios</span>
                </a>
                <a href="{{ route('propietario.apartamentos.index') }}" class="sidebar-item">
                    <i class="fas fa-home"></i>
                    <span>Mis Apartamentos</span>
                </a>
                <a href="{{ route('propietario.evaluaciones.index') }}" class="sidebar-item">
                    <i class="fas fa-star"></i>
                    <span>Evaluaciones</span>
                </a>
            @endif
        </div>

        <!-- Sección de Alquileres -->
        <div class="sidebar-section">
            <h3 class="sidebar-section-title">Alquileres</h3>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('contrato.index') }}" class="sidebar-item">
                    <i class="fas fa-file-contract"></i>
                    <span>Contratos</span>
                </a>
                <a href="{{ route('solicitudes.index') }}" class="sidebar-item">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Solicitudes</span>
                </a>
                <a href="{{ route('estado_alquiler.index') }}" class="sidebar-item">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Estados de Alquiler</span>
                </a>
                <a href="{{ route('recordatorio_pago.index') }}" class="sidebar-item">
                    <i class="fas fa-bell"></i>
                    <span>Recordatorios</span>
                </a>
            @else
                <a href="{{ route('propietario.contratos.index') }}" class="sidebar-item">
                    <i class="fas fa-file-contract"></i>
                    <span>Contratos</span>
                </a>
                <a href="{{ route('propietario.solicitudes.index') }}" class="sidebar-item">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Solicitudes</span>
                </a>
                <a href="{{ route('propietario.estados-alquiler.index') }}" class="sidebar-item">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Estados de Alquiler</span>
                </a>
                <a href="{{ route('propietario.recordatorios.index') }}" class="sidebar-item">
                    <i class="fas fa-bell"></i>
                    <span>Recordatorios</span>
                </a>
            @endif
        </div>

        <!-- Sección de Reportes y Problemas -->
        <div class="sidebar-section">
            <h3 class="sidebar-section-title">Reportes</h3>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('reporte_problema.index') }}" class="sidebar-item">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Problemas</span>
                </a>
                <a href="{{ route('queja.index') }}" class="sidebar-item">
                    <i class="fas fa-comments"></i>
                    <span>Quejas</span>
                </a>
            @else
                <a href="{{ route('propietario.reportes.index') }}" class="sidebar-item">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Reportes de Problemas</span>
                </a>
                <a href="{{ route('propietario.quejas.index') }}" class="sidebar-item">
                    <i class="fas fa-comments"></i>
                    <span>Quejas y Sugerencias</span>
                </a>
            @endif
        </div>
    </nav>

    <!-- Botón de Cerrar sesión -->
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center py-2 px-4 text-gray-400 hover:text-white hover:bg-gray-700 rounded-md transition">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>
</div>

<!-- Contenido principal -->
<div id="content">
    <div class="p-6">
        <!-- Eliminado el título de Dashboard que aparecía en todas las vistas -->
        <header class="mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <!-- Botón de toggle para el sidebar -->
                    <button id="sidebarToggle" class="mr-4 p-2 bg-white rounded-md shadow hover:bg-gray-100 focus:outline-none">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                        {{ auth()->user()->hasRole('admin') ? 'Administrador' : 'Propietario' }}
                    </span>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-2 hidden sm:inline">{{ auth()->user()->nombre }}</span>
                        <img src="{{ auth()->user()->avatar ? asset('storage/avatars/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nombre) . '&color=7F9CF5&background=EBF4FF' }}"
                            alt="Avatar" class="h-8 w-8 rounded-full">
                    </div>
                </div>
            </div>
        </header>

        <!-- Contenido de cada vista -->
        @yield('dashboard-content')
    </div>
</div>
@endsection

@push('scripts')
<!-- Script para manejar el sidebar -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('overlay');

        // Verificar el tamaño de la pantalla al cargar
        checkScreenSize();

        // Verificar el tamaño de la pantalla al cambiar el tamaño de la ventana
        window.addEventListener('resize', checkScreenSize);

        function checkScreenSize() {
            if (window.innerWidth < 768) {
                sidebar.classList.remove('mobile-visible');
                content.classList.add('expanded');
            }
        }

        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('mobile-visible');
                overlay.classList.toggle('visible');
            } else {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
            }
        });

        // Cerrar sidebar al hacer click en el overlay
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-visible');
            overlay.classList.remove('visible');
        });
    });

    // Configuración base para todos los gráficos
    Chart.defaults.font.family = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    Chart.defaults.color = '#6B7280';
    Chart.defaults.plugins.legend.position = 'bottom';
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
</script>

@stack('dashboard-scripts')
@endpush
