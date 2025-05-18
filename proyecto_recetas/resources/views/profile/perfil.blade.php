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

    {{--  Ruta a vista edición comentada

        @if(auth()->id() == $perfil->id_user)
            <a href="{{ route('perfil.edicionPerfil', ['id' => $perfil->id_user]) }}" class="btn btn-primary">Editar perfil</a>
        @endif 
    --}}
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarPeril">
            Editar perfil
        </button>
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

<!-- Modal -->
<div class="modal fade" id="editarPeril" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edición de perfil</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('perfil.actualizar', ['id' => $perfil->id_user]) }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $perfil->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ old('descripcion', $perfil->biografia) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen de perfil actual</label><br>
                    @if($perfil->img_perfil)
                        <img src="{{ asset('storage/' . $perfil->img_perfil) }}" width="100" alt="Imagen perfil">
                    @else
                        <p>No hay imagen de perfil</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="img_perfil" class="form-label">Nueva imagen de perfil</label>
                    <input type="file" name="img_perfil" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Banner actual</label><br>
                    @if($perfil->img_banner)
                        <img src="{{ asset('storage/' . $perfil->img_banner) }}" width="200" alt="Banner">
                    @else
                        <p>No hay banner</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="img_banner" class="form-label">Nuevo banner</label>
                    <input type="file" name="img_banner" class="form-control">
                </div>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Guardar cambios</button>
        </div>
    </form>
    </div>
  </div>
</div>
@endsection
