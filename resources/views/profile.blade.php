@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Perfil de {{ auth()->user()->name }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Correo Electrónico:</strong> {{ auth()->user()->email }}</p>
            <p><strong>Tipo de Usuario:</strong> {{ auth()->user()->role ?? 'Cliente' }}</p>
            <p><strong>Fecha de Registro:</strong> {{ auth()->user()->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Editar Perfil</a>
            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger" type="submit">Eliminar Cuenta</button>
            </form>
        </div>
    </div>
</div>
@endsection
