@extends('layouts.app')

@section('titulo', 'Administración')

@section('admin')

<div class="container py-5">
    <div class="row align-items-center justify-content-between">
        <!-- Botón Recetas -->
        <div class="col-12 col-md-4 d-flex justify-content-center mb-4 mb-md-0">
            <a href="{{ url('admin/recetas') }}" class="btn btn-warning btn-lg px-4 shadow-sm fs-4 btn-hover-animate">
                Recetas
            </a>
        </div>

        <!-- Título central -->
        <div class="col-12 col-md-4 text-center">
            <h1 class="fw-bold display-5 mb-0">Elija el listado</h1>
        </div>

        <!-- Botón Usuarios -->
        <div class="col-12 col-md-4 d-flex justify-content-center mb-4 mb-md-0">
            <a href="{{ url('admin/usuarios') }}" class="btn btn-warning btn-lg px-4 shadow-sm fs-4 btn-hover-animate">
                Usuarios
            </a>
        </div>
    </div>
</div>
@endsection