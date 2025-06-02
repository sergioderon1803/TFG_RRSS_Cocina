@extends('layouts.app')

@section('titulo', 'Listado de recetas')

@section('content')
<div class="container-fluid my-3 px-3 mb-5">
    <div class="d-flex flex-column align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="/images/logo.svg" alt="Logo WeCook" class="img-fluid" style="height: 80px;">
            <span class="fs-4 d-none d-md-inline ms-2" id="sidebarLogoText">WeCook</span>
        </div>
        <p class="text-center text-muted mt-2" style="font-size: 0.8rem; max-width: 600px;">
            Explora nuestras deliciosas recetas y encuentra tu próxima inspiración culinaria.
        </p>
    </div>
    <div class="row gx-5 gy-4">
        <!-- Columna de recetas -->
        <div class="col-12 col-xl-8">
            <div class="row row-cols-1 row-cols-sm-3 g-3">
                @foreach ($recetas as $receta)
                    <div class="col">
                        <div class="card h-100 shadow-sm d-flex flex-column border-0 rounded-3" 
                            style="cursor: pointer;" onclick="window.location='{{ url('receta/' . $receta->id) }}'">

                            @if ($receta->imagen)
                                <img src="{{ asset(Str::startsWith($receta->imagen, 'recetas/') ? 'storage/' . $receta->imagen : $receta->imagen) }}"
                                    class="card-img-top"
                                    alt="Imagen de {{ $receta->titulo }}"
                                    style="height: 130px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                            @endif

                            <div class="card-body d-flex flex-column justify-content-between p-2">
                                <div class="mb-2 text">
                                    <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                                        <a href="{{ url('usuario/' . ($receta->user->id ?? '#')) }}" 
                                        class="text-decoration-none text-muted">
                                            <img src="{{ asset('images/default-profile.jpg') }}"
                                                alt="Imagen de perfil"
                                                class="rounded-circle me-2"
                                                style="width: 25px; height: 25px; object-fit: cover;">
                                            {{ $receta->user->name ?? 'usuario' }}
                                        </a>
                                    </div>
                                    <h6 class="card-title mb-1" style="font-size: 0.95rem;">
                                        <strong>{{ Str::limit($receta->titulo, 40) }}</strong>
                                    </h6>
                                </div>

                                <div class="d-flex justify-content-between mt-auto pt-2 px-1">
                                    <button class="btn p-0 border-0 bg-transparent" title="Me gusta">
                                        <i class="bi bi-heart text-danger"></i>
                                        <small>{{ $receta->usuariosQueGustaron->count() }}</small>
                                    </button>

                                    <button class="btn p-0 border-0 bg-transparent" title="Guardar receta">
                                        <i class="bi bi-bookmark text-success"></i>
                                        <small >{{ $receta->usuariosQueGuardaron->count() }}</small>
                                    </button>
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
