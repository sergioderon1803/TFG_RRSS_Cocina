@extends('legal.layout')

@section('page-title', 'Política de Cookies')

@section('legal-content')
    <div class="legal-section">
        <h2><i class="bi bi-shield-lock-fill me-2"></i>¿Qué son las cookies?</h2>
        <p class="mb-4">Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas nuestra web. Nos ayudan a mejorar tu experiencia.</p>

        <h2 class="mt-4"><i class="bi bi-list-check me-2"></i>Tipos de cookies que usamos</h2>
        <ul class="list-unstyled mb-4">
            <li class="mb-3">
                <i class="bi bi-shield-fill-check me-2"></i>
                <strong>Cookies esenciales:</strong> Necesarias para el funcionamiento básico del sitio.
            </li>
            <li class="mb-3">
                <i class="bi bi-graph-up me-2"></i>
                <strong>Cookies analíticas:</strong> Nos ayudan a entender cómo usas el sitio.
            </li>
            <li class="mb-3">
                <i class="bi bi-sliders me-2"></i>
                <strong>Cookies de preferencias:</strong> Recuerdan tus ajustes y preferencias.
            </li>
        </ul>
    </div>
@endsection
