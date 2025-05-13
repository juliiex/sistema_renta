@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="container mt-5">
    <h1>Perfil de {{ $user->name }}</h1>
    <p><strong>Correo Electrónico:</strong> {{ $user->email }}</p>
    <p><strong>Tipo de Usuario:</strong> Cliente</p> <!-- Aquí puedes cambiar el rol si tienes un campo en la BD -->
    <p><strong>Fecha de Registro:</strong> {{ $user->created_at->format('d/m/Y') }}</p>

    <a href="{{ route('welcome') }}" class="btn btn-primary">Volver a la Página Principal</a>
</div>
@endsection
