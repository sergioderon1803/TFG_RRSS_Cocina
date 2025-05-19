<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('titulo', 'WeCook!')</title>

        <!-- Fonts -->
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}
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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
            <div class="d-flex flex-column min-vh-100 bg-peach {{ Request::is('admin*') ? 'bg-body-secondary' : 'bg-peach' }}">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="flex-grow-1">
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
            </main>
            @include('layouts.footer')
        </div>
        @yield('js')
    </body>
</html>
