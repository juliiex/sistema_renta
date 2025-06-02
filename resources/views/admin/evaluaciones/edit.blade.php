<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evaluación</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Evaluación</h2>
            <a href="{{ route('evaluaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('evaluaciones.update', $evaluacion->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario:</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required
                    {{ !auth()->user()->hasRole(['admin', 'propietario']) ? 'disabled' : '' }}>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $evaluacion->usuario_id == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo }})
                        </option>
                    @endforeach
                </select>

                @if(!auth()->user()->hasRole(['admin', 'propietario']))
                    <input type="hidden" name="usuario_id" value="{{ $evaluacion->usuario_id }}">
                    <p class="text-xs text-gray-500 mt-1">Como inquilino, no puedes cambiar el usuario de la evaluación.</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="apartamento_id" class="block text-sm font-medium text-gray-700 mb-1">Apartamento:</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required
                    {{ !auth()->user()->hasRole(['admin', 'propietario']) ? 'disabled' : '' }}>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}" {{ $evaluacion->apartamento_id == $apartamento->id ? 'selected' : '' }}>
                            Apartamento {{ $apartamento->numero_apartamento }} -
                            {{ $apartamento->edificio->nombre ?? 'Edificio no especificado' }} -
                            Piso {{ $apartamento->piso }}
                        </option>
                    @endforeach
                </select>

                @if(!auth()->user()->hasRole(['admin', 'propietario']))
                    <input type="hidden" name="apartamento_id" value="{{ $evaluacion->apartamento_id }}">
                    <p class="text-xs text-gray-500 mt-1">Como inquilino, no puedes cambiar el apartamento de la evaluación.</p>
                @endif
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Calificación:</label>
                <div class="rating">
                    <input type="radio" id="star5" name="calificacion" value="5" {{ $evaluacion->calificacion == 5 ? 'checked' : '' }}/>
                    <label for="star5" title="5 estrellas"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star4" name="calificacion" value="4" {{ $evaluacion->calificacion == 4 ? 'checked' : '' }}/>
                    <label for="star4" title="4 estrellas"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star3" name="calificacion" value="3" {{ $evaluacion->calificacion == 3 ? 'checked' : '' }}/>
                    <label for="star3" title="3 estrellas"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star2" name="calificacion" value="2" {{ $evaluacion->calificacion == 2 ? 'checked' : '' }}/>
                    <label for="star2" title="2 estrellas"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star1" name="calificacion" value="1" {{ $evaluacion->calificacion == 1 ? 'checked' : '' }}/>
                    <label for="star1" title="1 estrella"><i class="fas fa-star"></i></label>
                </div>
                <p class="text-xs text-gray-500 mt-1">Selecciona una calificación de 1 a 5 estrellas.</p>
            </div>

            <div class="mb-4">
                <label for="comentario" class="block text-sm font-medium text-gray-700 mb-1">Comentario:</label>
                <textarea name="comentario" id="comentario" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('comentario', $evaluacion->comentario) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres.</p>
            </div>

            <div class="mb-4">
                <label for="fecha_evaluacion" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Evaluación:</label>
                <input type="date" name="fecha_evaluacion" id="fecha_evaluacion" value="{{ $evaluacion->fecha_evaluacion->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('evaluaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Actualizar Evaluación</button>
            </div>
        </form>
    </div>
</body>
</html>
