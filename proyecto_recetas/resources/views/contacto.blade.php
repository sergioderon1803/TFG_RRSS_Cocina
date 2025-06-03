@extends('layouts.app')

@section('titulo', 'Contacto')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert custom-alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-4 px-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope-paper-fill fs-2 me-3"></i>
                        <div>
                            <h1 class="h3 mb-1 fw-bold">Contáctanos</h1>
                            <p class="mb-0 opacity-75">Estamos aquí para ayudarte</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('contacto.enviar') }}" method="POST" class="contact-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-person text-secondary me-2"></i>Nombre
                                </label>
                                <input type="text" class="form-control custom-input" id="nombre" name="nombre" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope text-secondary me-2"></i>Email
                                </label>
                                <input type="email" class="form-control custom-input" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="asunto" class="form-label">
                                <i class="bi bi-chat-text text-secondary me-2"></i>Asunto
                            </label>
                            <input type="text" class="form-control custom-input" id="asunto" name="asunto" required>
                        </div>

                        <div class="mb-4">
                            <label for="mensaje" class="form-label">
                                <i class="bi bi-pencil-square text-secondary me-2"></i>Mensaje
                            </label>
                            <textarea class="form-control custom-input" id="mensaje" name="mensaje" rows="5" required></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="bi bi-send-fill me-2"></i>Enviar mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
