@php
    $perfilId = auth()->check() && auth()->user()->perfil ? auth()->user()->perfil->id_user : (auth()->check() ? auth()->id() : null);
@endphp
<nav class="d-block d-lg-none navbar navbar-light bg-dark border-top fixed-bottom rounded-top-3 shadow-sm">
    <ul class="nav justify-content-around w-100 text-white">
        <li class="nav-item">
            <a href="{{ route('recetas.lista') }}" class="nav-link text-center {{ request()->routeIs('recetas.lista') ? 'text-black' : 'text-white' }}">
                <i class="bi bi-house-door-fill fs-3"></i> <!-- HOME -->
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('recetas.recetasGuardadas') }}" class="nav-link text-center {{ request()->routeIs('recetas.recetasGuardadas') ? 'text-black' : 'text-white' }}">
                <i class="bi bi-bookmarks-fill fs-3"></i> <!-- GUARDADOS -->
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('about') }}" data-bs-toggle="modal" data-bs-target="#crearReceta" class="nav-link text-center {{ request()->routeIs('about') ? 'text-black' : 'text-white' }}">
                <i class="bi bi-plus-circle fs-3"></i><!-- CREAR RECETA -->
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('perfil.ver', ['id' => $perfilId]) }}" class="nav-link text-center {{ request()->routeIs('perfil.ver') ? 'text-black' : 'text-white' }}">
                <i class="bi bi-person-fill fs-3"></i><!-- PERFIL -->
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin') }}" class="nav-link text-center {{ request()->routeIs('admin') ? 'text-black' : 'text-white' }}">
                <i class="bi bi-gear-fill fs-3"></i> <!-- SETTINGS -->
            </a>
        </li>
    </ul>
</nav>