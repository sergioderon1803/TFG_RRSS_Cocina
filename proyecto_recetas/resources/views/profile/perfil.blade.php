@extends('layouts.app')

@section('titulo', 'Perfil de ' . $perfil->name)

@section('perfil')
<section class="h-100 gradient-custom-2">
    <div class="row d-flex justify-content-center">
      <div class="col col-lg-12 col-xl-12">
          <div class="rounded-top text-white d-flex flex-row" style="background-image: url('{{ asset('storage/' . $perfil->img_banner) }}'); height: 300px;">
            <div class="ms-4 d-flex flex-column">
                @if ($perfil->img_perfil)
                    <img src="{{ asset('storage/' . $perfil->img_perfil) }}" class="rounded-circle" style="width:255px; height: 255px; margin: 150px 0 20px 0;  object-fit:cover;" alt="Perfil de {{ $perfil->name }}">
                @endif
                {{-- Editar perfil --}}
                @auth
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPeril">
                        Editar perfil
                    </button>
                @endauth
            </div>
            <div class="ms-3" style="margin-top: 220px;">
              <h3 class="bg-peach text-black p-3 rounded letraSeguir">{{ $perfil->name }}</h3>
            </div>
          </div>
          <div class="p-4 text-black">
            <div class="d-flex justify-content-end text-center py-1 text-body">
                <div class="px-3">
                    <p class="mb-1 h5 letraSeguir">{{ $seguidores }}</p>
                    <p class="small text-muted mb-0 letraSeguir"><a href="{{ route('profile.seguidores', $perfil->id_user) }}">Seguidores</a></p>
                </div>
                <div>
                    <p class="mb-1 h5 letraSeguir">{{ $seguidos }}</p>
                    <p class="small text-muted mb-0 letraSeguir"><a href="{{ route('profile.seguidos', $perfil->id_user) }}">Seguidos</a></p>
                </div>
            </div>
          </div>

          {{-- Biografía --}}
          <div class="card-body p-4 text-black">
            <div class="mb-5 mt-5 text-body">
                <hr>
              <p class="lead fw-normal mb-1">Biografía:</p>
              <div class="p-4">
                <p class="font-italic mb-1">{{ $perfil->biografia ?? '¡Compartiendo recetas en WeCook!' }}</p>
              </div>
            </div>
            <hr>
            {{-- Listado de recetas del usuario --}}
            <div class="d-flex justify-content-between align-items-center mb-4 text-body">
              <p class="lead fw-normal mb-0">Recetas publicadas de {{ $perfil->name }}</p>
            </div>
            @if ($recetas->count() > 0)
        <div class="row">
            @foreach ($recetas as $receta)
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if ($receta->imagen)
                                <img src="{{ asset('storage/' . $receta->imagen) }}" 
                                     class="card-img-top img-publicacion" 
                                     alt="Imagen de {{ $receta->titulo }}">
                            @endif
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold">
                                    <a href="{{ url('receta/' . $receta->id) }}">
                                        {{ $receta->titulo }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            @else
                <p>Este usuario aún no ha publicado ninguna receta.</p>
            @endif
          </div>
    </div>
  </div>
</section>
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