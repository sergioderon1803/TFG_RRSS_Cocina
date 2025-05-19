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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPeril">
            Editar perfil
        </button>
    @endauth

    <div class="d-flex gap-4 mb-3">
        <div>
            <strong><a href="{{ route('profile.seguidores', $perfil->id_user) }}">Seguidores:</a></strong> {{ $seguidores }}
        </div>
        <div>
            <strong><a href="{{ route('profile.seguidos', $perfil->id_user) }}">Seguidos:</a></strong> {{ $seguidos }}
        </div>
    </div>

    @auth
        @if(Auth::id() != $perfil->id_user)
            <form id="seguir" method="POST" action="{{ $seguido ? route('usuario.dejarSeguir', $perfil->id_user) : route('usuario.seguir', $perfil->id_user) }}">
                @csrf
                @if($seguido)
                    @method('DELETE')
                @endif
                <button type="submit" class="btn {{ $seguido ? 'btn-secondary' : 'btn-outline-primary' }}">
                    {{ $seguido ? 'Dejar de seguir' : 'Seguir' }}
                </button>
            </form>
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


{{-- Aquí incluimos el modal desde el partial --}}
@include('profile.partials.modal-editar', ['perfil' => $perfil])
@endsection

@section('js')

    {{-- Librería de javascript que he importado (sweetAlert2) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if($seguido)
        <script>
            form = document.getElementById('seguir'); // Cojo todos los formBorrar
        

                form.addEventListener('submit', (e) =>{
                    e.preventDefault(); //Cuándo le den a submit, paro el evento y muestro el pop up

                    Swal.fire({
                    title: "¿Estás seguro de que deseas dejar de seguir a este usuario?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Dejar de seguir"
                    }).then((result) => {

                    if (result.isConfirmed) { // Si se acepta, se lanza el otro popup y se hace el submit
                        Swal.fire({
                        title: "Dejaste de seguir al usuario",
                        text: "",
                        icon: "success"
                        });
                        
                        setTimeout(() => {
                            form.submit();
                        }, 600);
                    }
                    });
                })
            
        </script>
    @endif
@endsection
