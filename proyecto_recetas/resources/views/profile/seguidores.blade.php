@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold">
            <a href="{{ route('perfil.ver', $perfil->user->id) }}" class="text-decoration-none text-dark">
                Seguidores de {{ $perfil->name ?? "Indefinido" }}
            </a>
        </h3>
    </div>

    @if ($seguidores->isEmpty())
        <div class="alert alert-info text-center">
            Este usuario no tiene seguidores.
        </div>
    @else
        <div class="list-group">
            @foreach ($seguidores as $seguidor)
                <a href="{{ route('perfil.ver', $seguidor->id) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                    {{-- Imagen de perfil --}}
                    @if ($seguidor->perfil->img_perfil)
                        <img src="{{ asset('storage/' . $seguidor->perfil->img_perfil) }}" 
                             class="rounded-circle shadow-sm" 
                             style="width: 50px; height: 50px; object-fit: cover;" 
                             alt="Imagen de perfil de {{ $seguidor->perfil->name }}">
                    @else
                        <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center text-white" style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                    {{-- Nombre --}}
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">{{ '@' . $seguidor->perfil->name ?? "Indefinido" }}</h6>
                        <p>{{ $seguidor->perfil->biografia }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
