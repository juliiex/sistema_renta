<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evaluación</title>
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
            <h2 class="text-2xl font-bold">Crear Evaluación</h2>
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

        <form action="{{ route('evaluaciones.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="mb-4">
                <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario:</label>
                <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="" disabled selected>Seleccionar Usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ auth()->id() == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->correo }})
                        </option>
                    @endforeach
                </select>
                @if(!auth()->user()->hasRole(['admin', 'propietario']))
                    <p class="text-xs text-gray-500 mt-1">Como inquilino, solo puedes crear evaluaciones a tu nombre.</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="apartamento_id" class="block text-sm font-medium text-gray-700 mb-1">Apartamento:</label>
                <select name="apartamento_id" id="apartamento_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="" disabled selected>Seleccionar Apartamento</option>
                    @foreach ($apartamentos as $apartamento)
                        <option value="{{ $apartamento->id }}">
                            Apartamento {{ $apartamento->numero_apartamento }} -
                            {{ $apartamento->edificio->nombre ?? 'Edificio no especificado' }} -
                            Piso {{ $apartamento->piso }}
                        </option>
                    @endforeach
                </select>
                @if(!auth()->user()->hasRole(['admin', 'propietario']))
                    <p class="text-xs text-gray-500 mt-1">Solo puedes evaluar apartamentos que has alquilado.</p>
                @endif
            </div>

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
            </div>

            <div class="mb-4">
                <label for="comentario" class="block text-sm font-medium text-gray-700 mb-1">Comentario:</label>
                <textarea name="comentario" id="comentario" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('comentario') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres.</p>
            </div>

            <div class="mb-4">
                <label for="fecha_evaluacion" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Evaluación:</label>
                <input type="date" name="fecha_evaluacion" id="fecha_evaluacion" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('evaluaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Guardar Evaluación</button>
            </div>
        </form>
    </div>
</body>
</html>
