@extends('layouts.app')

@section('title', 'Registro de Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4">Crear cuenta</h2>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input id="nombre" type="text" class="form-control" name="nombre" value="{{ old('nombre') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input id="correo" type="email" class="form-control" name="correo" value="{{ old('correo') }}" required>
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input id="telefono" type="text" class="form-control" name="telefono" value="{{ old('telefono') }}" required>
            </div>

            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input id="contraseña" type="password" class="form-control" name="contraseña" required>
            </div>

            <div class="mb-3">
                <label for="contraseña_confirmation" class="form-label">Confirmar contraseña</label>
                <input id="contraseña_confirmation" type="password" class="form-control" name="contraseña_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrarse</button>

            <div class="mt-3 text-center">
                <a href="{{ route('login') }}">¿Ya tienes una cuenta? Inicia sesión</a>
            </div>
        </form>
    </div>
</div>
@endsection
