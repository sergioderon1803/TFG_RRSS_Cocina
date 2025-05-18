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
