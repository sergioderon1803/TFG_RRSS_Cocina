@extends('layouts.app')

@section('seguidos')
<div class="container py-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold">
            <a href="{{ route('perfil.ver', $perfil->user->id) }}" class="text-decoration-none text-dark">
                Seguidores de {{ $perfil->name ?? "Indefinido" }}
            </a>
        </h3>
    </div>

    @if ($seguidos->isEmpty())
        <div class="alert alert-info text-center">
            Este usuario no sigue a nadie.
        </div>
    @else
        <div class="list-group">
            @foreach ($seguidos as $seguido)
                <a href="{{ route('perfil.ver', $seguido->id) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                    {{-- Imagen de perfil --}}
                    @if ($seguido->perfil->img_perfil)
                        <img src="{{ asset('storage/' . $seguido->perfil->img_perfil) }}" 
                             class="rounded-circle shadow-sm" 
                             style="width: 50px; height: 50px; object-fit: cover;" 
                             alt="Imagen de perfil de {{ $seguido->perfil->name }}">
                    @else
                        <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center text-white" style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                    {{-- Nombre --}}
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">{{ '@' . $seguido->perfil->name ?? "Indefinido" }}</h6>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
