@extends('layouts.app')

@section('title', 'About Us')

@section('about')
<div class="container py-5">
    <h1 class="text-center mb-4">Sobre Nosotros</h1>

    <p class="text-center lead">
        Somos tres amigos apasionados por la cocina y el desarrollo web. Esta red social permite compartir, descubrir y comentar recetas.
    </p>

    <div class="row text-center mt-5">
        <div class="col-md-4">
            <img src="{{ asset('storage/about/placeholder.jpg') }}" class="rounded-circle mb-3" alt="Sergio Álvarez de Ron">
            <h5>Sergio Álvarez de Ron</h5>
            <p>Alérgico a la vida</p>
        </div>
        <div class="col-md-4">
            <img src="{{ asset('storage/about/placeholder.jpg') }}" class="rounded-circle mb-3" alt="Jonathan Hidalgo">
            <h5>Jonathan Hidalgo</h5>
            <p>Drogadicto a las bebidas energéticas</p>
        </div>
        <div class="col-md-4">
            <img src="{{ asset('storage/about/placeholder.jpg') }}" class="rounded-circle mb-3" alt="Sergio Montoiro">
            <h5>Sergio Montoiro</h5>
            <p>Programador explotado</p>
        </div>
    </div>
</div>
@endsection
