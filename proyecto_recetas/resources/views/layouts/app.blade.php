<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('titulo', 'WeCook!')</title>
    @stack('css')
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>    

</head>
<body>
    <header>
        <nav class="navbar bg-light px-3 sticky-top shadow-sm">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
                <span class="ms-2 fw-bold">Mi Proyecto</span>
            </a>
        </nav>
    </header>
    
    <main>
        @yield('listado')
        @yield('detalle')
        @yield('formularioReceta')
        @yield('formularioEdicion')
    </main>

    <footer></footer>
</body>
</html>