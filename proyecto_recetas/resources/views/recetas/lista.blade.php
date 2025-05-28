@extends('layouts.app')

@section('titulo', 'Listado de recetas')

@section('content')
<div class="container-fluid my-3 px-3 mb-5">
    <div class="row gx-2 gy-4 justify-content-between">
        <!-- Columna de recetas -->
        <div class="col-12 col-xl-8">
            <div class="row row-cols-1 row-cols-sm-3 g-3">
                @foreach ($recetas as $receta)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            @if ($receta->imagen)
                                <img src="{{ asset(Str::startsWith($receta->imagen, 'recetas/') ? 'storage/' . $receta->imagen : $receta->imagen) }}"
                                     class="card-img-top"
                                     alt="Imagen de {{ $receta->titulo }}"
                                     style="height: 180px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h6 class="card-title text-center">
                                    <a href="{{ url('receta/' . $receta->id) }}" class="text-decoration-none text-dark">
                                        {{ $receta->titulo }}
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between">
                                    <h6><i class="bi bi-heart text-danger me-2"></i>{{ $receta->usuariosQueGustaron->count() }}</h6>
                                    <h6><i class="bi bi-bookmark text-success me-2"></i>{{ $receta->usuariosQueGuardaron->count() }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $recetas->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Columna de filtros -->
        <div class="col-12 col-xl-4">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <strong>Filtrar por categoría</strong>
                </div>
                <div class="card-body">
                    <!-- Contenido del filtro 1 -->
                    <select class="form-select">
                        <option selected>Selecciona categoría</option>
                        <option value="1">Entrantes</option>
                        <option value="2">Postres</option>
                        <!-- etc -->
                    </select>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <strong>Filtrar por dificultad</strong>
                </div>
                <div class="card-body">
                    <!-- Contenido del filtro 2 -->
                    <select class="form-select">
                        <option selected>Selecciona dificultad</option>
                        <option value="fácil">Fácil</option>
                        <option value="media">Media</option>
                        <option value="difícil">Difícil</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Crear Receta -->
<div class="modal fade" id="crearReceta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Receta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('recetas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="row g-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título" required>
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <input type="text" name="tipo" id="tipo" class="form-control" required>
                    </div>
                </div>

                <div class="justify-center">
                    <label for="imagen" class="form-label">Imagen:</label>
                    <img id="preview" class="mt-2 imagenPrevia" style="max-width: 250px; max-height: 250px;" src="{{ asset('images/pantallaGris.jpg') }}" alt="Imagen previa">
                    <input type="file" accept="image/*" id="imagen" name="imagen" class="form-control" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ingredientes" class="form-label">Ingredientes:</label>
                        <textarea name="ingredientes" id="ingredientes" class="form-control" rows="6" required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="procedimiento" class="form-label">Procedimiento:</label>
                        <textarea name="procedimiento" id="procedimiento" class="form-control" rows="6" required></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar receta</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('imagen').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');

    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'flex';
    } else {
      preview.src = '';
      preview.style.display = 'none';
    }
  });
</script>
@endsection
