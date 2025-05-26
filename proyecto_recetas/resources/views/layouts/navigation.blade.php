<nav class="d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 250px; height: 100vh; background-color: #F07B3F; position: fixed; top: 0; left: 0; box-shadow: 3px 0 6px rgba(0, 0, 0, 0.12); z-index: 1040;">
    @php
        $perfilId = auth()->check() && auth()->user()->perfil ? auth()->user()->perfil->id_user : (auth()->check() ? auth()->id() : null);
    @endphp

    <!-- Logo -->
    <a href="{{ route('recetas.lista') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <img src="/images/logo.svg" alt="Logo WeCook" class="img-fluid" style="height: 80px; margin-right: 1rem;">
        <span class="fs-4 d-none d-md-inline">WeCook</span>
    </a>

    <hr>

    <!-- Enlaces de navegación -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('recetas.lista') }}" class="nav-link text-white {{ request()->routeIs('recetas.lista') ? 'active bg-dark' : '' }}">
                {{ __('Principal') }}
            </a>
        </li>
        <li>
            <a href="{{ route('perfil.ver', ['id' => $perfilId]) }}" class="nav-link text-white {{ request()->routeIs('perfil.ver') ? 'active bg-dark' : '' }}">
                {{ __('Perfil') }}
            </a>
        </li>
        <li>
            <a href="{{ route('about') }}" class="nav-link text-white {{ request()->routeIs('about') ? 'active bg-dark' : '' }}">
                {{ __('About') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin') }}" class="nav-link text-white {{ request()->routeIs('admin') ? 'active bg-dark' : '' }}">
                {{ __('Admin') }}
            </a>
        </li>
    </ul>

    <!-- Área del usuario al fondo -->
    <div class="mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                {{ __('Cerrar sesión') }}
            </button>
        </form>
    </div>
</nav>
