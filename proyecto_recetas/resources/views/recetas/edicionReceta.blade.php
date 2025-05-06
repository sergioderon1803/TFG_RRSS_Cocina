@extends('layouts.app')

@section('titulo', 'Editar Receta')

@section('formularioEdicion')
<div class="container mt-4">
    <h2>Editar {{ $receta->titulo }}</h2>
    
    <form action="{{ route('recetas.actualizar', $receta->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="titulo" value="{{ old('titulo', $receta->titulo) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tipo</label>
            <input type="text" name="tipo" value="{{ old('tipo', $receta->tipo) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Ingredientes</label>
            <textarea name="ingredientes" class="form-control">{{ old('ingredientes', $receta->ingredientes) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Procedimiento</label>
            <textarea name="procedimiento" class="form-control">{{ old('procedimiento', $receta->procedimiento) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control">
            @if ($receta->imagen)
                <img src="{{ asset('storage/' . $receta->imagen) }}" class="img-thumbnail mt-2" width="150">
            @endif
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('recetas.lista') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
