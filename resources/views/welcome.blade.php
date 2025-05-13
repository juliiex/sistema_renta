<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Bienvenidos a Nuestro Sistema de Renta')

@section('content')
<div class="container">
    <div class="row">
        <!-- Solo mostrar esta columna cuando NO hay sidebar (usuario no autenticado) -->
        @guest
        <div class="col-md-3">
            <div class="list-group mb-4">
                <a href="{{ route('login') }}" class="list-group-item list-group-item-action">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="list-group-item list-group-item-action">Registrarse</a>
            </div>

            <!-- Información del Sistema para usuarios no autenticados -->
            <div class="card">
                <div class="card-header">Información del Sistema</div>
                <div class="card-body">
                    <p><strong>Total de Apartamentos:</strong> {{ $totalApartamentos ?? 0 }}<br>
                       <strong>Apartamentos Disponibles:</strong> {{ $apartamentosDisponibles ?? 0 }}<br>
                       <strong>Apartamentos Ocupados:</strong> {{ $apartamentosOcupados ?? 0 }}</p>
                </div>
            </div>
        </div>
        @endguest

        <!-- Contenido Principal - Ancho completo para usuarios autenticados -->
        <div class="@guest col-md-9 @else col-md-12 @endguest">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Bienvenidos a Nuestro Sistema de Renta</h3>
                </div>
                <div class="card-body">
                    <p>Encuentra los mejores apartamentos disponibles para alquilar. Ofrecemos un servicio fácil y seguro.</p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del Sistema para usuarios autenticados -->
            @auth
            <div class="card mb-4">
                <div class="card-header">Información del Sistema</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Total de Apartamentos</h5>
                                    <h2>{{ $totalApartamentos ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Apartamentos Disponibles</h5>
                                    <h2>{{ $apartamentosDisponibles ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Apartamentos Ocupados</h5>
                                    <h2>{{ $apartamentosOcupados ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endauth

            <!-- Listado de apartamentos -->
            @isset($apartamentos)
                <div class="card">
                    <div class="card-header">
                        <h4>Apartamentos Disponibles</h4>
                    </div>
                    <div class="card-body">
                        @if($apartamentos->count() > 0)
                            <div class="row">
                                @foreach($apartamentos as $apartamento)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <img src="{{ $apartamento->imagen ?? 'https://via.placeholder.com/400x200' }}"
                                                 class="card-img-top"
                                                 alt="Imagen de Apartamento {{ $apartamento->numero_apartamento }}">
                                            <div class="card-body">
                                                <h5 class="card-title">Apartamento #{{ $apartamento->numero_apartamento }}</h5>
                                                <p class="card-text">
                                                    <strong>Piso:</strong> {{ $apartamento->piso }}<br>
                                                    <strong>Precio:</strong> ${{ number_format($apartamento->precio, 2) }} /mes<br>
                                                    <strong>Estado:</strong>
                                                    <span class="badge {{ $apartamento->estado == 'disponible' ? 'bg-success' : 'bg-warning' }}">
                                                        {{ ucfirst($apartamento->estado) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ route('apartamentos.detalle', $apartamento->id) }}" class="btn btn-primary w-100">
                                                    Ver Más Detalles
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                No hay apartamentos disponibles en este momento.
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    No se encontraron apartamentos para mostrar.
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection
