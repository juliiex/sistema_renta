@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Contratos pendientes de firma</h1>
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
        <div class="bg-white shadow overflow-hidden rounded-lg mb-6">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Tus contratos por firmar
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Es necesario que firmes estos contratos para formalizar el alquiler del apartamento.
                </p>
            </div>

            <ul class="divide-y divide-gray-200">
                @foreach($contratos as $contrato)
                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="min-w-0 flex-1 px-4">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    Apartamento {{ $contrato->apartamento->numero_apartamento }}
                                </p>
                                <p class="mt-1 flex items-center text-sm text-gray-500">
                                    <span>{{ $contrato->apartamento->edificio->nombre }}</span>
                                    <span class="mx-1">&bull;</span>
                                    <span>Piso {{ $contrato->apartamento->piso }}</span>
                                </p>
                                <p class="mt-1 flex items-center text-sm text-gray-500">
                                    <span>Fecha inicio: {{ $contrato->fecha_inicio->format('d/m/Y') }}</span>
                                    <span class="mx-1">&bull;</span>
                                    <span>Fecha fin: {{ $contrato->fecha_fin->format('d/m/Y') }}</span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('usuario.firma.firmar', $contrato->id) }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                Firmar ahora
                            </a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Una vez que firmes el contrato, se te asignará el rol de inquilino y deberás iniciar sesión nuevamente.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes contratos pendientes de firma</h3>
            <p class="text-gray-500 mb-6">Actualmente no hay ningún contrato pendiente de tu firma.</p>
            <a href="{{ route('usuario.apartamentos.explorar') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                Explorar apartamentos
            </a>
        </div>
    @endif
</div>
@endsection
