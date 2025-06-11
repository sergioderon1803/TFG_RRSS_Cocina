@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center min-vh-100 bg-light">
    <div class="text-center">
        <h2 class="mb-4 text-secondary">500<br>Parece que aquí no hay nada :( ...<br><small class="text-muted">toma una ayuda para volver</small></h2>
        <a href="{{ route('recetas.lista') }}" class="btn btn-lg botonVerMas mt-3">
            <i class="bi bi-house me-2"></i>Volver al inicio
        </a>
    </div>
</div>
@endsection