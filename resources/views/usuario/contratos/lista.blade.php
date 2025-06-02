@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mis contratos</h1>
        <a href="{{ route('home') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al inicio
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(count($contratos) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($contratos as $contrato)
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition duration-300">
                    <div class="h-40 bg-gray-300 relative">
                        @if($contrato->apartamento->imagen)
                            <img src="{{ asset('storage/' . $contrato->apartamento->imagen) }}" alt="Apartamento {{ $contrato->apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full bg-gray-200">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-0 right-0 bg-{{ $contrato->estado == 'activo' ? 'green' : 'red' }}-500 text-white px-3 py-1 text-sm font-bold">
                            {{ ucfirst($contrato->estado) }}
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg text-gray-800">Apartamento {{ $contrato->apartamento->numero_apartamento }}</h3>

                            <div>
                                @if($contrato->estado_firma == 'firmado')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Firmado
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pendiente
                                    </span>
                                @endif
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">{{ $contrato->apartamento->edificio->nombre }}</p>

                        <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                            <div>
                                <span class="text-gray-500">Inicio: </span>
                                <span class="font-medium">{{ $contrato->fecha_inicio->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Fin: </span>
                                <span class="font-medium">{{ $contrato->fecha_fin->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                            <div>
                                <span class="text-gray-500">Precio: </span>
                                <span class="font-medium">${{ number_format($contrato->apartamento->precio, 0) }}/mes</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Tamaño: </span>
                                <span class="font-medium">{{ $contrato->apartamento->tamaño }} m²</span>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('usuario.contratos.detalle', $contrato->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded transition duration-150">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $contratos->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes contratos</h3>
            <p class="text-gray-500 mb-6">No tienes ningún contrato de alquiler activo o inactivo. Explora apartamentos disponibles para realizar una solicitud.</p>
            <a href="{{ route('usuario.apartamentos.explorar') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                Explorar apartamentos
            </a>
        </div>
    @endif
</div>
@endsection
