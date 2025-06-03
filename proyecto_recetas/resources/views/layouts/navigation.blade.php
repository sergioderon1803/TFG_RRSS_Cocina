<!-- Sidebar -->
<nav id="sidebarNav" class="sidebar d-flex flex-column p-3 text-white rounded-end-3">
    <!-- Botón toggle dentro del nav -->
    <button id="sidebarToggle"
            class="btn align-self-start mb-3 text-white"
            style="width: 40px; height: 40px;">
        <i class="bi bi-list"></i>
    </button>
    @php
        $perfilId = auth()->check() && auth()->user()->perfil ? auth()->user()->perfil->id_user : (auth()->check() ? auth()->id() : null);
    @endphp

    <!-- Logo -->
    <a href="{{ route('recetas.lista') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none" id="sidebarLogo" style="transition: all 0.3s ease;">
        <img src="/images/logo.svg" alt="Logo WeCook" class="img-fluid" style="height: 80px; margin-right: 1rem; transition: all 0.3s ease;">
        <span class="fs-4 d-none d-md-inline" id="sidebarLogoText">WeCook</span>
    </a>

    <hr>

    <!-- Enlaces de navegación -->
    <ul class="nav nav-pills flex-column mb-auto" id="sidebarMenu">
        <li class="nav-item">
            <a href="{{ route('recetas.lista') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('recetas.lista') ? 'active bg-dark' : '' }}">
                <i class="bi bi-house-door-fill me-2 fs-4"></i>
                <span class="link-wrapper"><span class="link-text"> {{ __('Principal') }} </span></span>
            </a>
        </li>
        <li>
            <a href="{{ route('perfil.ver', ['id' => $perfilId]) }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('perfil.ver') ? 'active bg-dark' : '' }}">
                <i class="bi bi-person-fill me-2 fs-4"></i>
                <span class="link-wrapper"><span class="link-text"> {{ __('Perfil') }} </span></span>
            </a>
        </li>
        <li>
            <a href="{{ route('recetas.recetasGuardadas') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('recetas.recetasGuardadas') ? 'active bg-dark' : '' }}">
                <i class="bi bi-bookmarks-fill me-2 fs-4"></i>
                <span class="link-wrapper"><span class="link-text"> {{ __('Guardados') }} </span></span>
            </a>
        </li>
        <li>
            <a href="{{ route('about') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('about') ? 'active bg-dark' : '' }}">
                <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                <span class="link-wrapper"><span class="link-text"> {{ __('About') }} </span></span>
            </a>
        </li>
        <br>
        @if (!request()->routeIs('about'))
        <li>
            @auth
                <button
                    class="btn fondoExito mb-3 w-100 text-white d-flex align-items-center fw-bold"
                    data-bs-toggle="modal"
                    data-bs-target="#crearReceta">
                    <i class="bi bi-plus-circle me-2 fs-4"></i>
                    <span class="link-text">CREAR RECETA</span>
                </button>
            @endauth
        </li>
        @endif
    </ul>

    <!-- Área del usuario al fondo -->
    <div class="mt-auto">
        <ul class="nav nav-pills flex-column mb-auto" id="sidebarMenu">
            <li>
                <a href="{{ route('admin') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('admin') ? 'active bg-dark' : '' }}">
                    <i class="bi bi-gear-fill me-2 fs-4"></i>
                    <span class="link-text"> {{ __('Admin') }} </span>
                </a>
            </li>
            <li>
                <a href="{{ route('ajustes.cuenta') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('ajustes.cuenta') ? 'active bg-dark' : '' }}">
                    <i class="bi bi-person-gear me-2 fs-4"></i>
                    <span class="link-text"> {{ __('Ajustes de cuenta') }} </span>
                </a>
            </li>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            
            <button type="submit" class="nav-link text-white d-flex align-items-center">
                <i class="bi bi-power me-2 fs-4"></i>
                <span class="logout-text"> {{ __('Cerrar sesión') }} </span>
            </button>
        </form>
        
    </div>
</nav>

<!-- Modal Crear Receta -->
@include('modals.crear-receta')


<script>
    // Script para el toggle del sidebar
    const sidebar = document.getElementById('sidebarNav');
    const toggleBtn = document.getElementById('sidebarToggle');
    const body = document.body;

    // Restaurar estado desde localStorage
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        body.classList.add('sidebar-collapsed');
    }

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        const collapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', collapsed);
        body.classList.toggle('sidebar-collapsed', collapsed);
    });
</script>
