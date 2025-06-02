<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contrato</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #firma-canvas {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            cursor: crosshair;
            height: 200px;
            width: 100%;
            background-color: #fff;
            touch-action: none;
        }
    </style>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Contrato</h2>
            <a href="{{ route('contrato.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver a la lista</a>
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

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contrato.update', $contrato->id) }}" method="POST" class="space-y-4" id="form-contrato">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <select name="usuario_id" id="usuario_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_id', $contrato->usuario_id) == $usuario->id ? 'selected' : '' }}>
                                ID: {{ $usuario->id }} - {{ $usuario->nombre }} ({{ $usuario->correo }})
                                @if($contrato->usuario_id == $usuario->id) (Usuario actual) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="apartamento_id" class="block text-sm font-medium text-gray-700 mb-1">Apartamento</label>
                    <select name="apartamento_id" id="apartamento_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach ($apartamentos as $apartamento)
                            <option value="{{ $apartamento->id }}" {{ old('apartamento_id', $contrato->apartamento_id) == $apartamento->id ? 'selected' : '' }}>
                                {{ $apartamento->numero_apartamento }} - Piso {{ $apartamento->piso }}
                                @if($contrato->apartamento_id == $apartamento->id) (Actual) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio', $contrato->fecha_inicio->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', $contrato->fecha_fin->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            @if($contrato->firma_imagen)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Firma Actual</label>
                <div class="border rounded-md overflow-hidden p-2 bg-gray-50">
                    <img src="{{ asset('storage/' . $contrato->firma_imagen) }}" alt="Firma actual" class="max-h-40 mx-auto">
                </div>
            </div>
            @endif

            <div>
                <label for="actualizar_firma" class="block text-sm font-medium text-gray-700 mb-1">¿Actualizar firma?</label>
                <select id="actualizar_firma" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="no" selected>No, mantener firma actual</option>
                    <option value="si">Sí, crear nueva firma</option>
                </select>
            </div>

            <div id="firma-container" class="space-y-3" style="display: none;">
                <p class="text-sm text-gray-600 mb-2">Dibuje la nueva firma en el recuadro a continuación:</p>

                <div class="w-full border border-gray-300 rounded-md p-1 bg-white">
                    <canvas id="firma-canvas"></canvas>
                </div>
                <input type="hidden" name="firma_base64" id="firma_base64">

                <div class="flex space-x-3">
                    <button type="button" id="btn-limpiar" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">
                        Limpiar firma
                    </button>
                </div>
            </div>

            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="estado" id="estado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="activo" {{ old('estado', $contrato->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $contrato->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('contrato.show', $contrato->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 transition">Cancelar</a>
                <button type="button" id="btn-guardar" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Actualizar Contrato</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('firma-canvas');
            const ctx = canvas.getContext('2d');
            const btnLimpiar = document.getElementById('btn-limpiar');
            const btnGuardar = document.getElementById('btn-guardar');
            const formContrato = document.getElementById('form-contrato');
            const inputFirma = document.getElementById('firma_base64');
            const actualizarFirma = document.getElementById('actualizar_firma');
            const firmaContainer = document.getElementById('firma-container');

            let dibujando = false;
            let firmaDibujada = false;

            // Mostrar/ocultar el contenedor de firma
            actualizarFirma.addEventListener('change', function() {
                if (this.value === 'si') {
                    firmaContainer.style.display = 'block';
                    setTimeout(setupCanvas, 100);
                } else {
                    firmaContainer.style.display = 'none';
                }
            });

            // Configurar canvas al tamaño del contenedor
            function setupCanvas() {
                const container = canvas.parentElement;
                canvas.width = container.offsetWidth - 2; // -2 para el borde
                canvas.height = 200;

                // Configurar contexto del canvas
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000';
            }

            // Inicializar canvas
            setupCanvas();
            window.addEventListener('resize', function() {
                if (firmaContainer.style.display !== 'none') {
                    setupCanvas();
                }
            });

            // Eventos para mouse
            canvas.addEventListener('mousedown', function(e) {
                dibujando = true;
                ctx.beginPath();
                const rect = canvas.getBoundingClientRect();
                ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
                e.preventDefault();
            });

            canvas.addEventListener('mousemove', function(e) {
                if (!dibujando) return;
                const rect = canvas.getBoundingClientRect();
                ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
                ctx.stroke();
                firmaDibujada = true;
                e.preventDefault();
            });

            canvas.addEventListener('mouseup', function(e) {
                dibujando = false;
                e.preventDefault();
            });

            canvas.addEventListener('mouseleave', function(e) {
                dibujando = false;
                e.preventDefault();
            });

            // Eventos para touch
            canvas.addEventListener('touchstart', function(e) {
                dibujando = true;
                ctx.beginPath();
                const rect = canvas.getBoundingClientRect();
                const touch = e.touches[0];
                ctx.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
                e.preventDefault();
            });

            canvas.addEventListener('touchmove', function(e) {
                if (!dibujando) return;
                const rect = canvas.getBoundingClientRect();
                const touch = e.touches[0];
                ctx.lineTo(touch.clientX - rect.left, touch.clientY - rect.top);
                ctx.stroke();
                firmaDibujada = true;
                e.preventDefault();
            });

            canvas.addEventListener('touchend', function(e) {
                dibujando = false;
                e.preventDefault();
            });

            // Limpiar firma
            btnLimpiar.addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                firmaDibujada = false;
                inputFirma.value = '';
            });

            // Enviar formulario
            btnGuardar.addEventListener('click', function() {
                if (actualizarFirma.value === 'si') {
                    if (!firmaDibujada) {
                        alert('Por favor, dibuje una firma antes de guardar o seleccione "No actualizar firma".');
                        return;
                    }

                    // Obtener imagen del canvas en formato base64
                    const firmaBase64 = canvas.toDataURL('image/png');
                    inputFirma.value = firmaBase64;
                }

                formContrato.submit();
            });
        });
    </script>
</body>
</html>
