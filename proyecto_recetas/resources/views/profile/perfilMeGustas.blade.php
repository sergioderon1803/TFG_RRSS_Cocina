@extends('layouts.app')

@section('titulo', 'Perfil de ' . $perfil->name)

@section('perfilMeGustas')
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
                        <button type="submit" class="btn btn-danger btn-sm">Dejar de seguir</button>
                    @else
                        <button type="submit" class="btn btn-success btn-sm">Seguir</button>
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

<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#seguidores">
    Ver seguidores
</button>

<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#seguidos">
    Ver seguidos
</button>

@if (Auth::id() === $perfil->id_user)
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#recetasGuardadas">
        Ver recetas guardadas
    </button>
@endif

<hr class="mt-4">

{{-- Recetas del usuario --}}
<div class="px-4">
    <h5 class="fw-bold mb-3"><a href="{{ route('perfil.ver', $perfil->id_user) }}">Recetas publicadas</a> | <strong>Me gustas</strong></h5>


@if ($perfil->user->recetasGustadas->count() > 0)
    <div class="row">
        @foreach ($perfil->user->recetasGustadas as $receta)
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
                            <small class="text-muted">Publicado por {{ $receta->autor_receta }}</small>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-muted">A este usuario no le gusta ninguna receta de momento.</p>
@endif
</div>

<!-- Modal Seguidos-->
<div class="modal fade" id="seguidos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Personas que sigue {{$perfil->name}}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
            @if ($perfil->user->seguidos->count() == 0)
                <div class="alert alert-info text-center">
                    Este usuario no sigue a nadie.
                </div>
            @else
                <div class="list-group">
                    @foreach ($perfil->user->seguidos as $seguido)
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
    </div>
  </div>
</div>



<!-- Modal Seguidores-->
<div class="modal fade" id="seguidores" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Seguidores de {{$perfil->name}}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
            @if ($perfil->user->seguidores ->count() == 0)
                <div class="alert alert-info text-center">
                    Este usuario no tiene seguidores.
                </div>
            @else
                <div class="list-group">
                    @foreach ($perfil->user->seguidores as $seguidor)
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
    </div>
  </div>
</div>

<!-- Modal Recetas Guardadas-->
<div class="modal fade" id="recetasGuardadas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Recetas Guardadas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
           @if ($perfil->user->recetasGuardadas->count() > 0)
            <div class="row">
                @foreach ($perfil->user->recetasGuardadas as $receta)
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
                                    <small class="text-muted">Publicado por {{ $receta->autor_receta }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
                    </div>
                @else
                    <p class="text-muted">Aún no has guardado ninguna receta</p>
                @endif
            </div>
        </div>
    </div>
  </div>
</div>


@include('profile.partials.modal-editar', ['perfil' => $perfil])
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($seguido)
    <script>
        const form = document.getElementById('seguir');

        if(form != null){  // ESTA COMPROBACIÓN ES NECESARIA, SI SE QUITA, DA ERROR CUANDO ENTRAS A TU PROPIO PERFIL, NO PETA LA PÁGINA PERO EL ERROR LO DA

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
        }
    </script>
@endif
@endsection