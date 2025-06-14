<div class="modal fade" id="crearReceta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  ">
    <div class="modal-content">
      <form action="{{ route('recetas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header mb-2">
          <h1 class="list-group-item text-center modal-title fs-4 text-decoration-none col-12 py-3 m-0 rounded-0" style="color:white;">Crear receta</h1>
        </div>
        <div class="modal-body">
              <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título" required>
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="" selected disabled>Selecciona tipo de receta</option>
                            <option value="Postres y dulces">Postres y dulces</option>
                            <option value="Arroz">Arroz</option>
                            <option value="Pasta">Pasta</option>
                            <option value="Carnes y aves">Carnes y aves</option>
                            <option value="Pescado y marisco">Pescado y marisco</option>
                            <option value="Verduras y hortalizas">Verduras y hortalizas</option>
                            <option value="Ensaladas">Ensaladas</option>
                            <option value="Huevos y tortillas">Huevos y tortillas</option>
                            <option value="Tapas y aperitivos">Tapas y aperitivos</option>
                            <!-- etc -->
                        </select>
                    </div>
                </div>

                <div class="d-flex flex-column align-items-center">
                    <label for="imagen" class="form-label">Imagen:</label>
                    <img id="preview" class="mt-2 img-fluid" style="max-width: 250px; max-height: 250px;" src="{{ asset('images/default-img.jpg') }}" alt="Imagen previa">
                    <input type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" id="imagen" name="imagen" class="form-control" required>
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
        <div class="modal-footer d-flex justify-content-between flex-nowrap p-1 m-1">
          <button type="submit" class="btn btn-guardar btn-sm fs-6 text-decoration-none col-4 py-3 rounded-2">Guardar receta</button>
          <button type="button" class="btn btn-cancelar btn-sm fs-6 text-decoration-none col-4 py-3 rounded-2" data-bs-dismiss="modal">Cancelar</button>
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
