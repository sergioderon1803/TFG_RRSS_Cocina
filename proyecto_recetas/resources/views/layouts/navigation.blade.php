<!-- Sidebar -->
<nav id="sidebarNav" class="d-flex flex-column p-3 text-white"
     style="width: 250px; height: 100vh; background-color: #F07B3F;
            position: fixed; top: 0; left: 0; box-shadow: 3px 0 6px rgba(0,0,0,0.12);
            z-index: 1040; transition: width 0.3s ease; overflow-x: hidden;">

    <!-- Botón toggle dentro del nav -->
    <button id="sidebarToggle"
            class="btn align-self-start mb-3"
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
                <span class="link-text"> {{ __('Principal') }} </span>
            </a>
        </li>
        <li>
            <a href="{{ route('perfil.ver', ['id' => $perfilId]) }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('perfil.ver') ? 'active bg-dark' : '' }}">
                <i class="bi bi-person-fill me-2 fs-4"></i>
                <span class="link-text"> {{ __('Perfil') }} </span>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('#') ? 'active bg-dark' : '' }}">
                <i class="bi bi-bookmarks-fill me-2 fs-4"></i>
                <span class="link-text"> {{ __('Guardados') }} </span>
            </a>
        </li>
        <li>
            <a href="{{ route('about') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('about') ? 'active bg-dark' : '' }}">
                <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                <span class="link-text"> {{ __('About') }} </span>
            </a>
        </li>
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
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            
            <button type="submit" class="nav-link text-white d-flex align-items-center">
                <i class="bi bi-power me-2 fs-4"></i>
                <span class="logout-text"> {{ __('Cerrar sesión') }} </span>
            </button>
        </form>
        
    </div>
</nav>

<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebarNav');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });
</script>