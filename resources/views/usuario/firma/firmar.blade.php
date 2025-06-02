@extends('layouts.app')

@push('styles')
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
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('usuario.firma.index') }}" class="flex items-center text-blue-600 hover:underline mr-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Firmar contrato</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Apartamento {{ $contrato->apartamento->numero_apartamento }}</h2>
                    <p class="text-gray-600">{{ $contrato->apartamento->edificio->nombre }} - Piso {{ $contrato->apartamento->piso }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    Pendiente de firma
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Fecha de inicio</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Fecha de fin</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Duración</h3>
                    <p class="mt-1 text-lg font-medium text-gray-800">{{ $contrato->fecha_inicio->diffInMonths($contrato->fecha_fin) }} meses</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Condiciones del contrato</h3>

                <div class="prose max-w-none">
                    <p>Por el presente documento, yo <strong>{{ Auth::user()->nombre }}</strong>, acepto alquilar el apartamento {{ $contrato->apartamento->numero_apartamento }} ubicado en {{ $contrato->apartamento->edificio->nombre }}, de acuerdo a las siguientes condiciones:</p>

                    <ol>
                        <li>El periodo de alquiler será desde <strong>{{ $contrato->fecha_inicio->format('d/m/Y') }}</strong> hasta <strong>{{ $contrato->fecha_fin->format('d/m/Y') }}</strong>.</li>
                        <li>El monto mensual de alquiler será de <strong>${{ number_format($contrato->apartamento->precio, 0) }}</strong>, pagadero dentro de los primeros 5 días de cada mes.</li>
                        <li>El apartamento será utilizado única y exclusivamente como vivienda.</li>
                        <li>El inquilino se compromete a mantener el apartamento en buen estado.</li>
                        <li>Cualquier modificación al inmueble deberá ser consultada previamente con el propietario.</li>
                        <li>El contrato podrá ser renovado previo acuerdo entre las partes.</li>
                    </ol>

                    <p>Al firmar este documento, confirmo que he leído, entendido y acepto todas las condiciones establecidas en este contrato.</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tu firma</h3>

                <p class="text-gray-600 mb-4">Por favor, dibuja tu firma en el recuadro de abajo para completar el contrato.</p>

                <form action="{{ route('usuario.firma.guardar', $contrato->id) }}" method="POST" id="form-firma">
                    @csrf

                    <div class="mb-4">
                        <div class="w-full border border-gray-300 rounded-md p-1 bg-white">
                            <canvas id="firma-canvas"></canvas>
                        </div>
                        <input type="hidden" name="firma_base64" id="firma_base64">
                    </div>

                    <div class="flex space-x-4">
                        <button type="button" id="btn-limpiar" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            Limpiar firma
                        </button>

                        <button type="button" id="btn-firmar" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                            Firmar y continuar
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-sm text-gray-500">
                    <p>Al firmar este contrato:</p>
                    <ul class="list-disc pl-5 mt-2">
                        <li>Confirmas que has leído y aceptado todos los términos.</li>
                        <li>Obtendrás el rol de inquilino y acceso a las funcionalidades exclusivas.</li>
                        <li>La firma no puede ser revocada. Por favor, asegúrate de estar de acuerdo con todos los términos antes de firmar.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('firma-canvas');
        const ctx = canvas.getContext('2d');
        const btnLimpiar = document.getElementById('btn-limpiar');
        const btnFirmar = document.getElementById('btn-firmar');
        const formFirma = document.getElementById('form-firma');
        const inputFirma = document.getElementById('firma_base64');

        let dibujando = false;
        let firmaDibujada = false;

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
        window.addEventListener('resize', setupCanvas);

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
        btnFirmar.addEventListener('click', function() {
            if (!firmaDibujada) {
                alert('Por favor, dibuje una firma antes de continuar.');
                return;
            }

            // Obtener imagen del canvas en formato base64
            const firmaBase64 = canvas.toDataURL('image/png');
            inputFirma.value = firmaBase64;

            // Confirmar antes de enviar
            if (confirm('¿Estás seguro de que deseas firmar este contrato? Una vez firmado, no podrás modificarlo.')) {
                formFirma.submit();
            }
        });
    });
</script>
@endpush
