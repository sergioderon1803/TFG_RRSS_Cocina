@extends('layouts.app')

@section('titulo', 'Listado de recetas')

@section('listado')
<div class="container my-5">
    <div class="row justify-content-center gy-4">
        <!-- Columna de recetas -->
        <div class="col-12 col-lg-8">
            <div class="row">
                @foreach ($recetas as $receta)
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if ($receta->imagen)
                            <img src="{{ asset('storage/' . $receta->imagen) }}" 
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
                            <p>Me gusta: {{$receta->usuariosQueGustaron->count()}}</p>
                            <p>Com: {{$receta->comentarios->count()}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $recetas->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Columna de filtros -->
        <div class="col-12 col-lg-3">
            @auth
                <a href="{{ url('recetas/crear') }}" 
                   class="btn btn-primary mb-3 w-100 text-white fw-bold">
                   CREAR RECETA
                </a>
            @endauth

            @php
                $filtros = ['Pasta', 'Fritos', 'Healthy', 'Primer Plato', 'Postre', 'Sin gluten'];
            @endphp

            @foreach ($filtros as $filtro)
                <a href="#" 
                   class="btn btn-outline-success mb-2 w-100 fw-bold categorias">
                   {{ $filtro }}
                </a>
            @endforeach

            <!-- Receta de la semana -->
            <div class="card shadow-sm border-info mt-4">
                <div class="card-header bg-info text-white text-center fw-bold">
                    RECETA DE LA SEMANA
                </div>
                <img src="#" class="card-img-top" alt="Receta de la semana" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h6 class="card-title fw-bold">Garbanzos con espinacas</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection