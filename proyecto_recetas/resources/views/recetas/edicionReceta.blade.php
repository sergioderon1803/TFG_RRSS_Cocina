@extends('layouts.app')

@section('titulo', 'Editar Receta')

@section('formularioEdicion')
<div class="mt-3 letraTamaño container" >
    <h1 class="titulo mb-5 text-center">Editar {{ $receta->titulo }}</h1>

    <form action="{{ route('recetas.actualizar', $receta->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="titulo" class="form-label">Título:</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $receta->titulo) }}" class="form-control" placeholder="Título" required>
        </div>

        <div class="mb-3" style="max-width: 700px;">
            <label for="imagen" class="form-label">Imagen:</label>
            <input type="file" accept="image/*" id="imgInput" name="imagen" class="form-control">
            @if ($receta->imagen)
            <img id="preview" class="mt-2 imagenPrevia" src="{{ asset('storage/' . $receta->imagen) }}" alt="Imagen previa">
            @endif
        </div>

         <!-- Contenedor de ingredientes y procedimiento -->
        <div class="mb-3 d-flex justify-content-between">
            <!-- Ingredientes -->
            <div class="flex-fill pe-3">
                <label for="ingredientes" class="form-label">Ingredientes:</label>
                <textarea name="ingredientes" id="ingredientes" class="form-control" rows="6" required>{{ old('ingredientes', $receta->ingredientes) }}</textarea>
            </div>

            <!-- Procedimiento -->
            <div class="flex-fill ps-3">
                <label for="procedimiento" class="form-label">Procedimiento:</label>
                <textarea name="procedimiento" id="procedimiento" class="form-control" rows="6" required>{{ old('procedimiento', $receta->procedimiento) }}</textarea>
            </div>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo:</label>
            <input type="text" name="tipo" id="tipo" class="form-control" value="{{ old('tipo', $receta->tipo) }}" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('recetas.lista') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
  document.getElementById('imgInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');

    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
    } else {
      preview.src = '';
      preview.style.display = 'none';
    }
  });
</script>
@endsection