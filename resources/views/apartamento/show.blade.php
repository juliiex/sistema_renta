@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">Detalles del Apartamento</div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Número de apartamento:</strong>
                        <p>{{ $apartamento->numero_apartamento }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Descripción:</strong>
                        <p>{{ $apartamento->descripcion ?? 'Sin descripción' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Tamaño (m²):</strong>
                        <p>{{ $apartamento->tamano }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Estado:</strong>
                        <p>{{ $apartamento->estado }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Imagen:</strong>
                        @if($apartamento->imagen)
                            <img src="{{ asset('storage/' . $apartamento->imagen) }}" class="mt-2 w-100 rounded" style="max-height: 300px;">
                        @else
                            <p>No hay imagen disponible.</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Edificio:</strong>
                        <p>{{ $apartamento->edificio->nombre }}</p>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('apartamento.index') }}" class="btn btn-secondary">Volver a la lista</a>
                        <a href="{{ route('apartamento.edit', $apartamento->id) }}" class="btn btn-primary">Editar Apartamento</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card { border-radius: 4px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
    .card-header { background: #f8f9fa; font-weight: bold; }
    .img-thumbnail { max-width: 100%; height: auto; }
    .btn-primary { background-color: #3b82f6; border-color: #3b82f6; }
    .btn-primary:hover { background-color: #2563eb; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
</style>
@endpush


