@extends('layouts.app')

@section('titulo', 'Crear receta')

@section('formularioReceta')
<div class="container mt-4">
    <h2>Crear nueva receta</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errores encontrados:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('recetas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="titulo" class="form-label">Título:</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo:</label>
            <input type="text" name="tipo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="ingredientes" class="form-label">Ingredientes:</label>
            <textarea name="ingredientes" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="procedimiento" class="form-label">Procedimiento:</label>
            <textarea name="procedimiento" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (opcional):</label>
            <input type="file" name="imagen" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar receta</button>
        <a href="{{ url('recetas') }}" class="btn btn-secondary">Volver al listado</a>
    </form>
</div>
@endsection
