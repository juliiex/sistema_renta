<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f3f4f6;margin:0;padding:0}
        .container{max-width:1200px;margin:auto;padding:2rem}
        h1{text-align:center;font-size:2rem;font-weight:bold;color:#1f2937;margin-bottom:2rem}
        .section-title{font-size:1.25rem;font-weight:600;margin:2rem 0 1rem;color:#4b5563;border-bottom:2px solid #e5e7eb;padding-bottom:.5rem}
        .menu-grid{display:grid;gap:1rem;grid-template-columns:repeat(1,1fr)}
        @media(min-width:640px){.menu-grid{grid-template-columns:repeat(2,1fr)}}
        @media(min-width:768px){.menu-grid{grid-template-columns:repeat(3,1fr)}}
        .menu-button{display:block;text-align:center;text-decoration:none;color:white;font-weight:600;padding:.75rem 1rem;border-radius:.75rem;box-shadow:0 4px 6px rgba(0,0,0,0.1);transition:.2s;margin-bottom:1rem;background:#3b82f6}
        .menu-button:hover{transform:scale(1.03);background:#2563eb}
        .primary{background:#2563eb}.primary:hover{background:#1d4ed8}
        .secondary{background:#4f46e5}.secondary:hover{background:#4338ca}
        .accent{background:#0369a1}.accent:hover{background:#075985}
        .admin{background:#6366f1}.admin:hover{background:#4f46e5}
        .profile{background:#059669}.profile:hover{background:#047857}
        .user-info{text-align:right;font-size:.9rem;color:#4b5563;margin-bottom:1rem}
        .logout-button{background:#ef4444;color:white;padding:.3rem .7rem;border-radius:.5rem;font-size:.8rem;text-decoration:none;margin-left:.5rem}
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info">
            Hola, {{ auth()->user()->nombre }} ({{ auth()->user()->roles->pluck('nombre')->implode(', ') }})
            <a href="{{ route('logout') }}" class="logout-button" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar sesión</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>

        <h1>Menú Principal</h1>

        @foreach (['success' => ['#d1fae5', '#065f46'], 'error' => ['#fee2e2', '#b91c1c']] as $type => [$bg, $color])
            @if(session($type))
                <div style="background:{{ $bg }};color:{{ $color }};padding:1rem;border-radius:.5rem;margin-bottom:1rem;">{{ session($type) }}</div>
            @endif
        @endforeach

        @if(auth()->user()->hasRole(['admin', 'propietario']))
            <h2 class="section-title">Gestión de Propiedades</h2>
            <div class="menu-grid">
                <a href="{{ route('edificio.index') }}" class="menu-button primary">Edificios</a>
                <a href="{{ route('apartamento.index') }}" class="menu-button primary">Apartamentos</a>
            </div>
        @endif

        @if(auth()->user()->hasRole(['posible_inquilino']))
            <h2 class="section-title">Apartamentos Disponibles</h2>
            <div class="menu-grid">
                <a href="{{ route('apartamento.index') }}" class="menu-button primary">Ver Apartamentos</a>
            </div>
        @endif

        <h2 class="section-title">
            @if(auth()->user()->hasRole(['admin', 'propietario'])) Contratos y Alquileres
            @elseif(auth()->user()->hasRole('inquilino')) Mis Contratos
            @else Solicitudes @endif
        </h2>
        <div class="menu-grid">
            @if(auth()->user()->hasRole(['admin', 'propietario', 'inquilino']))
                <a href="{{ route('contrato.index') }}" class="menu-button secondary">{{ auth()->user()->hasRole(['admin', 'propietario']) ? 'Contratos' : 'Mis Contratos' }}</a>
            @endif
            @if(auth()->user()->hasRole(['admin', 'propietario']))
                <a href="{{ route('estado_alquiler.index') }}" class="menu-button secondary">Estados de Alquiler</a>
            @endif
            <a href="{{ route('solicitudes.index') }}" class="menu-button secondary">
                {{ auth()->user()->hasRole(['admin', 'propietario']) ? 'Solicitudes de Alquiler' : 'Mis Solicitudes' }}
            </a>
            @if(auth()->user()->hasRole(['posible_inquilino', 'inquilino']))
                <a href="{{ route('solicitudes.create') }}" class="menu-button secondary">Nueva Solicitud</a>
            @endif
        </div>

        <h2 class="section-title">Pagos y Reportes</h2>
        <div class="menu-grid">
            @if(auth()->user()->hasRole(['admin', 'propietario']))
                <a href="{{ route('recordatorio_pago.index') }}" class="menu-button accent">Recordatorios de Pago</a>
            @endif
            <a href="{{ route('reporte_problema.index') }}" class="menu-button accent">
                {{ auth()->user()->hasRole(['admin', 'propietario']) ? 'Reportes de Problemas' : 'Mis Reportes' }}
            </a>
            @if(auth()->user()->hasRole(['inquilino']))
                <a href="{{ route('reporte_problema.create') }}" class="menu-button accent">Reportar Problema</a>
            @endif
            <a href="{{ route('queja.index') }}" class="menu-button accent">
                {{ auth()->user()->hasRole(['admin', 'propietario']) ? 'Quejas' : 'Mis Quejas' }}
            </a>
            @if(auth()->user()->hasRole(['admin', 'propietario']))
                <a href="{{ route('evaluaciones.index') }}" class="menu-button accent">Evaluaciones</a>
            @endif
        </div>

        @if(auth()->user()->hasRole('admin'))
            <h2 class="section-title">Administración del Sistema</h2>
            <div class="menu-grid">
                <a href="{{ route('usuarios.index') }}" class="menu-button admin">Usuarios</a>
                <a href="{{ route('rol.index') }}" class="menu-button admin">Roles</a>
                <a href="{{ route('permiso.index') }}" class="menu-button admin">Permisos</a>
                <a href="{{ route('rol_permiso.index') }}" class="menu-button admin">Roles y Permisos</a>
                <a href="{{ route('usuario_rol.index') }}" class="menu-button admin">Usuarios y Roles</a>
            </div>
        @endif

        <h2 class="section-title">Mi Cuenta</h2>
        <div class="menu-grid">
            <a href="{{ route('profile.show') }}" class="menu-button profile">Ver Mi Perfil</a>
            <a href="{{ route('profile.edit') }}" class="menu-button profile">Editar Mi Perfil</a>
        </div>
    </div>
</body>
</html>
