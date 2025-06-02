@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        @if($queja->usuario_id == Auth::id())
            <a href="{{ route('usuario.quejas.mis-quejas') }}" class="flex items-center text-blue-600 hover:underline mr-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a mis quejas
            </a>
        @else
            <a href="{{ route('usuario.quejas.index') }}" class="flex items-center text-blue-600 hover:underline mr-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a todas las quejas
            </a>
        @endif
        <h1 class="text-2xl font-bold text-gray-800">Detalle de la Queja</h1>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        {{ $queja->tipo }}
                    </span>
                    <span class="text-sm text-gray-500 ml-2">
                        {{ $queja->fecha_envio->format('d/m/Y H:i') }}
                    </span>
                </div>
                @if($queja->usuario_id == Auth::id())
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Mi queja
                    </span>
                @endif
            </div>

            <div class="p-6">
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <img src="{{ $queja->usuario->avatar ? asset('storage/avatars/' . $queja->usuario->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($queja->usuario->nombre) . '&color=7F9CF5&background=EBF4FF' }}"
                             alt="{{ $queja->usuario->nombre }}" class="h-10 w-10 rounded-full">
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $queja->usuario->nombre }}
                            @if($queja->usuario_id == Auth::id())
                                <span class="text-blue-600 text-sm ml-1">(Tú)</span>
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500">
                            Enviado hace {{ $queja->fecha_envio->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $queja->descripcion }}</p>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>ID de queja: QJ-{{ str_pad($queja->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    <div>
                        @if($queja->usuario_id == Auth::id() && $queja->fecha_envio->diffInHours(now()) < 24)
                            <button type="button" class="text-red-600 hover:text-red-800 text-sm">
                                Eliminar queja
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
