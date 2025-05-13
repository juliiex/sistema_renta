@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar Apartamento</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('apartamento.update', $apartamento->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Mostrar errores de validación -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="numero_apartamento">Número de apartamento:</label>
                            <input type="text" class="form-control" name="numero_apartamento" value="{{ old('numero_apartamento', $apartamento->numero_apartamento) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="piso">Piso:</label>
                            <input type="text" class="form-control" name="piso" value="{{ old('piso', $apartamento->piso) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="descripcion">Descripción:</label>
                            <textarea class="form-control" name="descripcion" rows="3">{{ old('descripcion', $apartamento->descripcion) }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tamaño">Tamaño (m²):</label>
                            <input type="number" class="form-control" name="tamaño" value="{{ old('tamaño', $apartamento->tamaño) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="precio">Precio:</label>
                            <input type="number" class="form-control" name="precio" value="{{ old('precio', $apartamento->precio) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edificio_id">Edificio:</label>
                            <select class="form-control" name="edificio_id" required>
                                <option value="">Seleccione un edificio</option>
                                @foreach($edificios as $edificio)
                                    <option value="{{ $edificio->id }}" {{ old('edificio_id', $apartamento->edificio_id) == $edificio->id ? 'selected' : '' }}>
                                        {{ $edificio->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="estado">Estado:</label>
                            <select class="form-control" name="estado" required>
                                <option value="Disponible" {{ old('estado', $apartamento->estado) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="Ocupado" {{ old('estado', $apartamento->estado) == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                                <option value="En mantenimiento" {{ old('estado', $apartamento->estado) == 'En mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen">Imagen:</label>
                            <input type="file" class="form-control" name="imagen">
                            @if($apartamento->imagen)
                                <img src="{{ asset('storage/' . $apartamento->imagen) }}" class="mt-2 w-100 rounded" style="max-height: 300px;">
                            @endif
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Actualizar Apartamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

