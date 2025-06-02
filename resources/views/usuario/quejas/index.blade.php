@extends('layouts.app')

@section('title', 'Quejas de la Comunidad')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Quejas de la Comunidad</h1>
        <a href="{{ route('usuario.quejas.crear') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nueva Queja
        </a>
    </div>

    <!-- Tarjetas de quejas -->
    <div class="grid gap-6">
        @forelse($quejas as $queja)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center mb-3">
                        <img src="{{ $queja->usuario->avatar ? asset('storage/avatars/' . $queja->usuario->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($queja->usuario->nombre) . '&color=7F9CF5&background=EBF4FF' }}"
                            alt="Avatar" class="h-10 w-10 rounded-full mr-3">
                        <div>
                            <p class="font-medium text-gray-900">{{ $queja->usuario->nombre }}</p>
                            <p class="text-sm text-gray-500">{{ $queja->fecha_envio->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="ml-auto px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                            {{ $queja->tipo }}
                        </span>
                    </div>

                    <p class="text-gray-700 mb-4">{{ $queja->descripcion }}</p>

                    <a href="{{ route('usuario.quejas.detalle', $queja->id) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium flex items-center">
                        Ver detalles
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <p class="mt-4 text-gray-500 text-lg">No hay quejas registradas en la comunidad.</p>
                <div class="mt-6">
                    <a href="{{ route('usuario.quejas.crear') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg text-sm font-medium">
                        Presentar la primera queja
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($quejas->hasPages())
        <div class="mt-6">
            {{ $quejas->links() }}
        </div>
    @endif
</div>
@endsection
