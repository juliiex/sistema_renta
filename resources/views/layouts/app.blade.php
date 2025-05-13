<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Renta')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s;
        }

        .no-sidebar {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar solo si está autenticado -->
    @auth
    <div class="bg-dark text-white p-3 sidebar">
        <h4>{{ auth()->user()->name }}</h4>
        <p class="text-muted small">{{ auth()->user()->email }}</p>
        <hr>
        <ul class="list-unstyled">
            <li class="mb-2"><a href="{{ url('/') }}" class="text-white text-decoration-none">Inicio</a></li>
            <li class="mb-2"><a href="{{ route('profile') }}" class="text-white text-decoration-none">Perfil</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0">Cerrar Sesión</button>
                </form>
            </li>
        </ul>
    </div>
    @endauth

    <!-- Contenido principal -->
    <div class="@auth main-content @else no-sidebar @endauth p-3">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
