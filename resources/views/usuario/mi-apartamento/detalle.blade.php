@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating input {
        display: none;
    }
    .rating label {
        cursor: pointer;
        width: 40px;
        font-size: 30px;
        color: #ccc;
    }
    .rating input:checked ~ label {
        color: #ffcc00;
    }
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffcc00;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('home') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a mis apartamentos
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Apartamento {{ $contrato->apartamento->numero_apartamento }}</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="md:flex">
            <div class="md:w-1/2">
                <div class="h-96 bg-gray-200 relative overflow-hidden">
                    @if($contrato->apartamento->imagen)
                        <img src="{{ asset('storage/' . $contrato->apartamento->imagen) }}" alt="Apartamento {{ $contrato->apartamento->numero_apartamento }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center w-full h-full bg-gray-200 text-gray-500">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-0 right-0 bg-{{ $contrato->estado == 'activo' ? 'green' : 'red' }}-500 text-white px-3 py-1 text-sm font-bold uppercase">
                        {{ $contrato->estado }}
                    </div>
                </div>
            </div>
            <div class="md:w-1/2 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Apartamento {{ $contrato->apartamento->numero_apartamento }}</h2>
                        <p class="text-gray-600 mb-4">{{ $contrato->apartamento->edificio->nombre }}</p>
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $calificacionPromedio)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @elseif($i - 0.5 <= $calificacionPromedio)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                            <span class="text-sm text-gray-600 ml-2">
                                {{ number_format($calificacionPromedio, 1) }} ({{ $totalEvaluaciones }} evaluaciones)
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xl font-bold">
                            ${{ number_format($contrato->apartamento->precio, 0) }} <span class="text-sm font-normal">/mes</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="grid grid-cols-2 gap-x-4 gap-y-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Edificio</h3>
                            <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->apartamento->edificio->nombre }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Piso</h3>
                            <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->apartamento->piso }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tamaño</h3>
                            <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->apartamento->tamaño }} m²</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Estado</h3>
                            <p class="mt-1 text-lg font-medium text-gray-800 capitalize">{{ $contrato->apartamento->estado }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Dirección</h3>
                        <div class="bg-gray-50 rounded-md p-3 flex items-start">
                            <svg class="w-5 h-5 text-gray-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-gray-700">{{ $contrato->apartamento->edificio->direccion }}</p>
                        </div>
                    </div>

                    @if($contrato->apartamento->descripcion)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Descripción</h3>
                            <div class="bg-gray-50 rounded-md p-3">
                                <p class="text-gray-700 whitespace-pre-line">{{ $contrato->apartamento->descripcion }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detalles del contrato -->
        <div class="border-t border-gray-200 px-6 py-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Detalles del contrato</h3>

                @if(isset($otrosContratos) && count($otrosContratos) > 0)
                    <a href="{{ route('usuario.contratos.lista') }}" class="text-blue-600 hover:underline text-sm font-medium">
                        Ver todos mis contratos
                    </a>
                @endif
            </div>

            <div class="bg-indigo-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Estado del contrato</h4>
                        <p class="font-medium flex items-center mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contrato->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($contrato->estado) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Fecha de inicio</h4>
                        <p class="font-medium mt-1">{{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Fecha de finalización</h4>
                        <p class="font-medium mt-1">{{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Historial de pagos -->
            @if(count($pagos) > 0)
                <h4 class="text-md font-semibold text-gray-700 mb-3">Últimos pagos</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mes</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de pago</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pagos as $pago)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            @if(isset($pago->mes_reportado))
                                                {{ $pago->mes_reportado }}
                                            @else
                                                {{ $pago->fecha_reporte->format('F Y') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $pago->estado_pago == 'pagado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($pago->estado_pago) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $pago->fecha_reporte->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(isset($pago->monto))
                                            ${{ number_format($pago->monto, 0) }}
                                        @else
                                            ${{ number_format($contrato->apartamento->precio, 0) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(count($pagos) > 3)
                    <div class="mt-3 text-center">
                        <a href="{{ route('usuario.contratos.detalle', $contrato->id) }}" class="text-blue-500 hover:underline text-sm">Ver historial completo</a>
                    </div>
                @endif
            @else
                <div class="text-center py-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-500">No hay registros de pagos disponibles.</p>
                </div>
            @endif
        </div>

        <!-- Sección para reportar un problema - NUEVA -->
        <div class="border-t border-gray-200 px-6 py-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">¿Tienes algún problema con este apartamento?</h3>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-600 mb-4">Si estás experimentando algún problema con este apartamento (daños, averías, servicios que no funcionan correctamente), puedes reportarlo para que sea atendido.</p>

                <a href="{{ route('usuario.reportes.nuevo', ['apartamento_id' => $contrato->apartamento_id]) }}" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Reportar un problema
                </a>
            </div>
        </div>

        <!-- Sección de Evaluación -->
        <div id="evaluar" class="border-t border-gray-200 px-6 py-5">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Evaluación del apartamento</h3>

            @if($evaluacionExistente)
                <div class="bg-indigo-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-800 mb-2">Tu evaluación</h4>

                    <div class="flex items-center mb-3">
                        <div class="flex mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $evaluacionExistente->calificacion)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600">
                            {{ $evaluacionExistente->fecha_evaluacion->format('d/m/Y') }}
                        </span>
                    </div>

                    <p class="text-gray-700 bg-white p-3 rounded-lg border border-gray-100">
                        "{{ $evaluacionExistente->comentario }}"
                    </p>
                </div>
            @elseif($puedeEvaluar)
                <form action="{{ route('usuario.mi-apartamento.evaluar', $contrato->id) }}" method="POST" class="bg-gray-50 p-4 rounded-lg">
                    @csrf
                    <p class="mb-4 text-gray-600">Comparte tu experiencia sobre este apartamento. Tu evaluación ayudará a futuros inquilinos.</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Calificación:</label>
                        <div class="rating">
                            <input type="radio" id="star5" name="calificacion" value="5" />
                            <label for="star5" title="5 estrellas"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star4" name="calificacion" value="4" />
                            <label for="star4" title="4 estrellas"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star3" name="calificacion" value="3" />
                            <label for="star3" title="3 estrellas"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star2" name="calificacion" value="2" />
                            <label for="star2" title="2 estrellas"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star1" name="calificacion" value="1" required />
                            <label for="star1" title="1 estrella"><i class="fas fa-star"></i></label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Selecciona una calificación de 1 a 5 estrellas.</p>

                        @error('calificacion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="comentario" class="block text-sm font-medium text-gray-700 mb-1">Comentario:</label>
                        <textarea name="comentario" id="comentario" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Comparte tu experiencia con este apartamento...">{{ old('comentario') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Mínimo 10 caracteres, máximo 500 caracteres.</p>

                        @error('comentario')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition duration-150">
                        Enviar evaluación
                    </button>
                </form>
            @else
                <div class="bg-yellow-50 p-4 rounded-lg text-center">
                    <p class="text-yellow-700">No puedes evaluar este apartamento en este momento.</p>
                </div>
            @endif
        </div>

        <!-- Otros contratos -->
        @if(isset($otrosContratos) && count($otrosContratos) > 0)
            <div class="border-t border-gray-200 px-6 py-5">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Mis otros contratos</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($otrosContratos as $otroContrato)
                        <div class="border rounded-lg overflow-hidden shadow-sm p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium">Apto. {{ $otroContrato->apartamento->numero_apartamento }} - {{ $otroContrato->apartamento->edificio->nombre }}</h4>
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $otroContrato->fecha_inicio->format('d/m/Y') }} - {{ $otroContrato->fecha_fin->format('d/m/Y') }}
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $otroContrato->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($otroContrato->estado) }}
                                </span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('usuario.mi-apartamento.detalle', $otroContrato->id) }}" class="text-blue-600 hover:underline text-sm">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
