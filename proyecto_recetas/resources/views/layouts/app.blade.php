<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'WeCook!')</title>
    @yield('css')

    <!-- Bootstrap y estilos -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @if(Request::is('admin*'))
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @endif

    @if(Request::is('perfil*'))
        <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    @endif

    @if(Request::is('recetas/crear*') || Request::is('recetas/*/editar'))
        <link rel="stylesheet" href="{{ asset('css/crearReceta.css') }}">
    @endif

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="d-flex flex-column min-vh-100 {{ Request::is('admin*') ? 'bg-body-secondary' : 'bg-peach' }}">
        @auth
            <!-- Sidebar -->
            <aside>
                @include('layouts.navigation')
            </aside>
        @endauth

        @guest
            @include('layouts.navigationGuest')
        @endguest

        <!-- Contenido principal -->
        <main class="flex-grow-1 main-content">
            <div class="container">
                @yield('listado')
                @yield('detalle')
                @yield('formularioReceta')
                @yield('formularioEdicion')
                @yield('admin')
                @yield('adminRecetas')
                @yield('adminUsuarios')
                @yield('perfil')
                @yield('about')
                @yield('edicionPerfil')
                @yield('seguidores')
                @yield('seguidos')
            </div>
        </main>
    </div>
    @yield('js')
</body>
</html>
