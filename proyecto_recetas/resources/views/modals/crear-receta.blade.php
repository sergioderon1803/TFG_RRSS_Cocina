<div class="modal fade" id="crearReceta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Receta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('recetas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="row g-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título" required>
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option hidden>Selecciona tipo de receta</option>
                            <option value="Bebidas">Bebidas</option>
                            <option value="Comida">Comida</option>
                            <option value="Entrantes">Entrantes</option>
                            <option value="Postres">Postres</option>
                            <option value="Saludable">Saludable</option>
                            <option value="Vegano">Vegano</option>
                            <!-- etc -->
                        </select>
                    </div>
                </div>

                <div class="justify-center">
                    <label for="imagen" class="form-label">Imagen:</label>
                    <img id="preview" class="mt-2 imagenPrevia" style="max-width: 250px; max-height: 250px;" src="{{ asset('images/pantallaGris.jpg') }}" alt="Imagen previa">
                    <input type="file" accept="image/*" id="imagen" name="imagen" class="form-control" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ingredientes" class="form-label">Ingredientes:</label>
                        <textarea name="ingredientes" id="ingredientes" class="form-control" rows="6" required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="procedimiento" class="form-label">Procedimiento:</label>
                        <textarea name="procedimiento" id="procedimiento" class="form-control" rows="6" required></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar receta</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('imagen')?.addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');

    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'flex';
    } else {
      preview.src = '';
      preview.style.display = 'none';
    }
  });
</script>
@endpush
