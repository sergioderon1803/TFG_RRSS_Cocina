@extends('layouts.app')

@section('titulo', 'Perfil de ' . $perfil->name)

@section('perfil')
<div class="container mt-4">

    {{-- Imagen de banner --}}
    @if ($perfil->img_banner)
        <div class="mb-4">
            <img src="{{ asset('storage/' . $perfil->img_banner) }}" class="img-fluid rounded" style="width:100%; max-height:300px; object-fit:cover;" alt="Banner de {{ $perfil->name }}">
        </div>
    @endif

    {{-- Imagen de perfil y nombre --}}
    <div class="d-flex align-items-center mb-3">
        @if ($perfil->img_perfil)
            <img src="{{ asset('storage/' . $perfil->img_perfil) }}" class="rounded-circle" style="width:100px; height:100px; object-fit:cover; margin-right: 20px;" alt="Perfil de {{ $perfil->name }}">
        @endif
        <h3 class="mb-0">{{ $perfil->name }}</h3>
    </div>

    {{-- Editar perfil --}}
    @auth
        @if(auth()->id() == $perfil->id_user)
            <a href="{{ route('perfil.edicionPerfil', ['id' => $perfil->id_user]) }}" class="btn btn-primary">Editar perfil</a>
        @endif
    @endauth

    {{-- Biografía --}}
    <p><strong>Biografía:</strong> {{ $perfil->biografia ?? '¡Compartiendo recetas en WeCook!' }}</p>

    <hr>

    {{-- Listado de recetas del usuario --}}
    <h3>Recetas publicadas de {{ $perfil->name }}</h3>
    @if ($recetas->count() > 0)
        <div class="row">
            @foreach ($recetas as $receta)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if ($receta->imagen)
                            <img src="{{ asset('storage/' . $receta->imagen) }}" class="card-img-top" alt="Imagen de {{ $receta->titulo }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $receta->titulo }}</h5>
                            <p class="card-text"><strong>Tipo:</strong> {{ $receta->tipo }}</p>
                            <a href="{{ url('receta/' . $receta->id) }}" class="btn btn-primary btn-sm">Ver receta</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Este usuario aún no ha publicado ninguna receta.</p>
    @endif

</div>
@endsection
