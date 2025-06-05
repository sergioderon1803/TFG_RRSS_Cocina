{{-- <div class="modal fade" id="recetasGuardadas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <small class="text-muted">Publicado por {{ $receta->autor->perfil->name }}</small>
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
</div> --}}