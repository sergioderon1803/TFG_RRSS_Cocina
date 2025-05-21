@extends('layouts.app')

@section('titulo', 'Perfil de ' . $perfil->name)

@section('perfil')
<div class="position-relative mb-5">
    {{-- Banner de usuario --}}
    <div class="w-100" style="height: 250px; background: url('{{ asset('storage/' . $perfil->img_banner) }}') no-repeat center center; background-size: cover;">
    </div>

    {{-- Imagen de perfil (superpuesta) --}}
    <div class="position-absolute top-100 start-0 translate-middle-y ps-4">
        <img src="{{ asset('storage/' . $perfil->img_perfil) }}" 
                class="rounded-circle border border-white shadow" 
                style="width: 120px; height: 120px; object-fit: cover;" 
                alt="Perfil de {{ $perfil->name }}">
    </div>

    {{-- Botón de editar o seguir, según el caso --}}
    @auth
        <div class="position-absolute top-0 end-0 p-3">
            @if (Auth::id() === $perfil->id_user)
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarPeril">
                    Editar perfil
                </button>
            @else
                <form id="seguir" method="POST" 
                    action="{{ $seguido ? route('usuario.dejarSeguir', $perfil->id_user) : route('usuario.seguir', $perfil->id_user) }}">
                    @csrf
                    @if ($seguido)
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Dejar de seguir</button>
                    @else
                        <button type="submit" class="btn btn-outline-success btn-sm">Seguir</button>
                    @endif
                </form>
            @endif
        </div>
    @endauth
</div>

{{-- Info del usuario y contadores --}}
<div class="d-flex justify-content-between align-items-start px-4 mt-4">
    {{-- Info del usuario --}}
    <div>
        <br>
        <p class="fw-bold mb-3">{{ '@' . Str::slug($perfil->name) }}</p>
        <p>{{ $perfil->biografia ?? '¡Compartiendo recetas en WeCook!' }}</p>
    </div>

    {{-- Seguidores / Seguidos (alineado a la derecha) --}}
    <div class="d-flex gap-4 text-center">
        <div class="d-flex flex-column">
            <strong class="text-dark">{{ $seguidores }}</strong>
            <a href="{{ route('profile.seguidores', $perfil->id_user) }}" class="text-decoration-none text-muted">Seguidores</a>
        </div>
        <div class="d-flex flex-column">
            <strong class="text-dark">{{ $seguidos }}</strong>
            <a href="{{ route('profile.seguidos', $perfil->id_user) }}" class="text-decoration-none text-muted">Seguidos</a>
        </div>
    </div>
</div>

<hr class="mt-4">

{{-- Recetas del usuario --}}
<div class="px-4">
    <h5 class="fw-bold mb-3">Recetas publicadas</h5>

    @if ($recetas->count() > 0)
        <div class="row">
            @foreach ($recetas as $receta)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if ($receta->imagen)
                            <img src="{{ asset('storage/' . $receta->imagen) }}" 
                                 class="card-img-top" 
                                 alt="Imagen de {{ $receta->titulo }}" 
                                 style="height: 180px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h6 class="card-title">
                                <a href="{{ url('receta/' . $receta->id) }}" class="text-decoration-none text-dark">
                                    {{ $receta->titulo }}
                                </a>
                            </h6>
                            <p class="card-text">
                                <small class="text-muted">Publicado por {{ $receta->autor }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Este usuario aún no ha publicado ninguna receta.</p>
    @endif
</div>

@include('profile.partials.modal-editar', ['perfil' => $perfil])
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($seguido)
<script>
  const form = document.getElementById('seguir');

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    Swal.fire({
      title: "¿Estás seguro de que deseas dejar de seguir a este usuario?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Dejar de seguir"
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: "Dejaste de seguir al usuario",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });

        setTimeout(() => {
          form.submit();
        }, 600);
      }
    });
  });
</script>
@endif
@endsection