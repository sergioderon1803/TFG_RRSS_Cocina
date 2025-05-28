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

@endsection
