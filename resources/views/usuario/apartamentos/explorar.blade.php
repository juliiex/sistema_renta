@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Apartamentos Disponibles</h1>
        <a href="{{ route('home') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6 border border-gray-200">
        <h3 class="font-bold text-lg text-gray-700 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filtrar Apartamentos
        </h3>
        <form action="{{ route('usuario.apartamentos.explorar') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="precio_min" class="block text-sm font-medium text-gray-700">Precio mínimo</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input
                        type="number"
                        name="precio_min"
                        id="precio_min"
                        min="0"
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-8 pr-12 sm:text-sm border-gray-300 rounded-md"
                        value="{{ max((int)request('precio_min', 0), 0) }}"
                        placeholder="Mínimo"
                        oninput="if(this.value<0)this.value=0;"
                    >
                </div>
            </div>

            <div>
                <label for="precio_max" class="block text-sm font-medium text-gray-700">Precio máximo</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input
                        type="number"
                        name="precio_max"
                        id="precio_max"
                        min="0"
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-8 pr-12 sm:text-sm border-gray-300 rounded-md"
                        value="{{ max((int)request('precio_max', 0), 0) }}"
                        placeholder="Máximo"
                        oninput="if(this.value<0)this.value=0;"
                    >
                </div>
            </div>

            <div>
                <label for="tamano_min" class="block text-sm font-medium text-gray-700">Tamaño mínimo</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="number" name="tamano_min" id="tamano_min" class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md" value="{{ request('tamano_min') }}" placeholder="m²">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">m²</span>
                    </div>
                </div>
            </div>

            <div>
                <label for="piso" class="block text-sm font-medium text-gray-700">Piso</label>
                <select name="piso" id="piso" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todos los pisos</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('piso') == $i ? 'selected' : '' }}>Piso {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Aplicar Filtros
                </button>
            </div>
        </form>

        @if(request()->anyFilled(['precio_min', 'precio_max', 'tamano_min', 'piso']))
            <div class="mt-4 flex justify-end">
                <a href="{{ route('usuario.apartamentos.explorar') }}" class="text-sm text-red-600 hover:text-red-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Limpiar filtros
                </a>
            </div>
        @endif
    </div>

    <!-- Resultados de la búsqueda -->
    <div>
        <div class="mb-6 flex justify-between items-center">
            <p class="text-gray-700">
                Mostrando <span class="font-medium">{{ $apartamentos->count() }}</span> de <span class="font-medium">{{ $apartamentos->total() }}</span> apartamentos
            </p>
            <div>
                <select id="sortOrder" onchange="window.location.href=this.options[this.selectedIndex].value" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="{{ route('usuario.apartamentos.explorar', array_merge(request()->query(), ['sort' => 'precio_asc'])) }}" {{ request('sort') == 'precio_asc' ? 'selected' : '' }}>
                        Precio: de menor a mayor
                    </option>
                    <option value="{{ route('usuario.apartamentos.explorar', array_merge(request()->query(), ['sort' => 'precio_desc'])) }}" {{ request('sort') == 'precio_desc' ? 'selected' : '' }}>
                        Precio: de mayor a menor
                    </option>
                    <option value="{{ route('usuario.apartamentos.explorar', array_merge(request()->query(), ['sort' => 'tamano_asc'])) }}" {{ request('sort') == 'tamano_asc' ? 'selected' : '' }}>
                        Tamaño: de menor a mayor
                    </option>
                    <option value="{{ route('usuario.apartamentos.explorar', array_merge(request()->query(), ['sort' => 'tamano_desc'])) }}" {{ request('sort') == 'tamano_desc' ? 'selected' : '' }}>
                        Tamaño: de mayor a menor
                    </option>
                </select>
            </div>
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

        <!-- Listado de Apartamentos -->
        @if($apartamentos->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron apartamentos</h3>
                <p class="text-gray-500 mb-6">No hay apartamentos disponibles que coincidan con tu búsqueda. Intenta con otros filtros.</p>
                <a href="{{ route('usuario.apartamentos.explorar') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    Ver todos los apartamentos
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($apartamentos as $apartamento)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 transition duration-300 hover:shadow-lg hover:border-blue-300">
                        <div class="relative">
                            <div class="h-48 bg-gray-300 relative overflow-hidden">
                                @if($apartamento->imagen)
                                    <img src="{{ asset('storage/' . $apartamento->imagen) }}" alt="Apartamento {{ $apartamento->numero_apartamento }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="flex items-center justify-center w-full h-full bg-gray-200 text-gray-500">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-0 right-0 bg-blue-600 text-white px-3 py-1 text-sm font-bold rounded-bl-lg">
                                    {{ number_format($apartamento->precio, 0) }} COP
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-lg text-gray-800">Apto. {{ $apartamento->numero_apartamento }}</h3>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disponible</span>
                            </div>

                            <p class="text-gray-700 text-sm mb-4">{{ $apartamento->edificio->nombre }}</p>

                            <!-- Calificación -->
                            <div class="flex items-center mb-3">
                                <div class="flex items-center mr-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $apartamento->calificacion_promedio)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @elseif($i - 0.5 <= $apartamento->calificacion_promedio)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">{{ $apartamento->calificacion_promedio }} ({{ $apartamento->total_evaluaciones }})</span>
                            </div>

                            <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Piso {{ $apartamento->piso }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"></path>
                                    </svg>
                                    {{ $apartamento->tamaño }} m²
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('usuario.apartamentos.detalle', $apartamento->id) }}" class="flex-1 flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver detalles
                                </a>
                                <form action="{{ route('usuario.apartamentos.solicitar', $apartamento->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full flex justify-center items-center bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Solicitar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8">
                {{ $apartamentos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
