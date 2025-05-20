@extends('layouts.app')

@section('titulo', 'Crear receta')

@section('formularioReceta')
<div class="mt-3 letraTamaño container" >
    <h1 class="titulo mb-5 text-center">Crear nueva receta</h1>

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
            <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título" required>
        </div>

        <div class="mb-3" style="max-width: 700px;">
            <label for="imagen" class="form-label">Imagen:</label>
            <input type="file" accept="image/*" id="imgInput" name="imagen" class="form-control" required>
            <img id="preview" class="mt-2 imagenPrevia" src="{{asset('images/pantallaGris.jpg')}}" alt="Imagen previa">
        </div>

         <!-- Contenedor de ingredientes y procedimiento -->
        <div class="mb-3 d-flex justify-content-between">
            <!-- Ingredientes -->
            <div class="flex-fill pe-3">
                <label for="ingredientes" class="form-label">Ingredientes:</label>
                <textarea name="ingredientes" id="ingredientes" class="form-control" rows="6" required></textarea>
            </div>

            <!-- Procedimiento -->
            <div class="flex-fill ps-3">
                <label for="procedimiento" class="form-label">Procedimiento:</label>
                <textarea name="procedimiento" id="procedimiento" class="form-control" rows="6" required></textarea>
            </div>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo:</label>
            <input type="text" name="tipo" id="tipo" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <button type="submit" class="btn btn-primary">Guardar receta</button>
            <a href="{{ url('recetas') }}" class="btn btn-secondary">Volver al listado</a>
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
