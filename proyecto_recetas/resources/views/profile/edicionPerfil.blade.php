@extends('layouts.app')

@section('edicionPerfil')
<div class="container">
    <h2>Editar Perfil</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('perfil.actualizar', ['id' => $perfil->id_user]) }}" method="POST" enctype="multipart/form-data">
        @csrf

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

        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="{{ route('perfil.ver', ['id' => $perfil->id_user]) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
