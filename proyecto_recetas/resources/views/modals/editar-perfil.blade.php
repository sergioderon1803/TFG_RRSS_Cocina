<div class="modal fade" id="editarPeril" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 id="exampleModalLabel" class="list-group-item text-center modal-title fs-4 text-decoration-none col-12 py-3 m-0 rounded-0" style="color:white;">Edición de perfil</h1>
      </div>

      <form action="{{ route('perfil.actualizar', ['id' => $perfil->id_user]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $perfil->name) }}" required>
            </div>

            <div class="col-md-8">
              <label for="descripcion" class="form-label">Biografía</label>
              <textarea name="descripcion" class="form-control" rows="1">{{ old('descripcion', $perfil->biografia) }}</textarea>
            </div>
          </div>

          <div class="row g-3 m-auto">
            <div class="col-md-6">
              <label class="form-label">Imagen de perfil actual</label><br>
              @if($perfil->img_perfil)
                <img src="{{ asset('storage/' . $perfil->img_perfil) }}" width="200" height="200" alt="Imagen perfil">
              @else
                <p>No hay imagen de perfil</p>
              @endif
            </div>

            <div class="col-md-6">
              <label for="img_perfil" class="form-label">Nueva imagen de perfil</label>
              <img id="previewImgPerfil" style="max-width: 200px; max-height:200px;" class="mt-2 imagenPrevia" src="{{asset('images/default-img.jpg')}}" alt="Imagen previa">
              <input id="img_perfil" type="file" name="img_perfil" class="form-control my-auto" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
            </div>
          </div>

          <div class="row g-3 m-auto">
            <div class="col-md-6">
              <label class="form-label">Banner actual</label><br>
              @if($perfil->img_banner)
                <img src="{{ asset('storage/' . $perfil->img_banner) }}" width="200" height="150" alt="Banner">
              @else
                <p>No hay banner</p>
              @endif
            </div>

            <div class="col-md-6">
              <label for="img_banner" class="form-label">Nuevo banner</label><br>
              <img id="previewBanner" style="max-width: 200px; max-height:200px;" class="mt-2 imagenPrevia" src="{{asset('images/default-img.jpg')}}" alt="Imagen previa">
              <input id="img_banner" type="file" name="img_banner" class="form-control my-auto" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
            </div>
          </div>

        </div>
          <div class="modal-footer d-flex justify-content-between flex-nowrap p-1 m-1">
          <button type="submit" class="btn btn-guardar btn-sm fs-6 text-decoration-none col-4 py-3 rounded-2" data-bs-dismiss="modal">Guardar cambios</button>
          <button type="button" class="btn btn-cancelar btn-sm fs-6 text-decoration-none col-4 py-3 rounded-2" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
