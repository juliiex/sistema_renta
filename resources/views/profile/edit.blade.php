@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="bg-gray-100 p-6 min-h-screen flex items-center justify-center">
    <div class="max-w-xl w-full bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Editar Perfil</h2>

        {{-- Mostrar mensajes de estado --}}
        @if(session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('status') }}
            </div>
        @endif

        {{-- Mostrar errores de validación --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-gray-700 mb-1 font-medium">Nombre</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $user->nombre) }}"
                    required
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Correo -->
            <div>
                <label for="email" class="block text-gray-700 mb-1 font-medium">Correo electrónico</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', $user->correo) }}"
                    required
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-gray-700 mb-1 font-medium">Foto de Perfil</label>
                <input
                    type="file"
                    name="avatar"
                    id="avatar"
                    accept="image/*"
                    class="w-full p-2 border rounded"
                >
                @if($user->avatar)
                    <img
                        src="{{ asset('storage/avatars/' . $user->avatar) }}"
                        alt="Avatar"
                        class="mt-2 w-24 h-24 rounded-full object-cover border"
                    >
                @endif
                @error('avatar')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition"
                >
                    Actualizar Perfil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
