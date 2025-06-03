@extends('legal.layout')

@section('page-title', 'Declaración de Accesibilidad')

@section('legal-content')
    <div class="legal-section">
        <h2><i class="bi bi-heart-fill me-2"></i>Nuestro compromiso</h2>
        <p class="mb-4">En WeCook nos esforzamos por hacer que nuestra plataforma sea accesible para todos los usuarios, independientemente de sus capacidades.</p>

        <h2 class="mt-4"><i class="bi bi-check2-circle me-2"></i>Características de accesibilidad</h2>
        <ul class="list-unstyled mb-4">
            <li class="mb-3">
                <i class="bi bi-keyboard-fill me-2"></i>
                <strong>Navegación por teclado:</strong> Acceso completo sin necesidad de ratón.
            </li>
            <li class="mb-3">
                <i class="bi bi-zoom-in me-2"></i>
                <strong>Texto escalable:</strong> El contenido se adapta al zoom del navegador.
            </li>
            <li class="mb-3">
                <i class="bi bi-image-alt me-2"></i>
                <strong>Textos alternativos:</strong> Todas las imágenes incluyen descripciones.
            </li>
        </ul>

        <div class="alert alert-info">
            <h3 class="h6 mb-2"><i class="bi bi-question-circle-fill me-2"></i>¿Necesitas ayuda?</h3>
            <p class="mb-0">Si encuentras algún problema de accesibilidad, por favor <a href="{{ route('contacto') }}" class="alert-link">contáctanos</a>.</p>
        </div>
    </div>
@endsection
