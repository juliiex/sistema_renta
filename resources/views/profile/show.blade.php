@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Perfil de {{ auth()->user()->nombre }}</h4>
        </div>
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <p><strong>Correo Electrónico:</strong> {{ auth()->user()->correo }}</p>

                <p><strong>Tipo de Usuario:</strong>
                    {{ auth()->user()->roles->pluck('nombre')->join(', ') ?? 'Cliente' }}
                </p>

                <p><strong>Fecha de Registro:</strong> {{ auth()->user()->created_at->format('d/m/Y') }}</p>
            </div>

            <div>
                @if (auth()->user()->avatar)
                    <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}"
                         alt="Avatar de {{ auth()->user()->nombre }}"
                         class="rounded-circle"
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 150px; height: 150px;">
                        Sin Avatar
                    </div>
                @endif
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Editar Perfil</a>
            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('¿Estás seguro de eliminar tu cuenta?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger" type="submit">Eliminar Cuenta</button>
            </form>
        </div>
    </div>
</div>
@endsection
