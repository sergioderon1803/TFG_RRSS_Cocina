<nav class="navbar navbar-expand-lg" style="background-color: #ff8d2f; box-shadow: 0 3px 6px rgba(0, 0, 0, 0.12); margin-bottom: 0; padding-bottom: 0.3rem;">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('recetas.lista') }}">
            <img src="/images/logo.svg" alt="Logo WeCook" class="img-fluid" style="height: 80px; margin-right: 1rem;">
        </a>

        <!-- Botón hamburguesa -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido" aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido del nav -->
        <div class="collapse navbar-collapse justify-content-between" id="navbarContenido">
            <!-- Links de navegación -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('recetas.lista') ? 'active' : '' }}" href="{{ route('recetas.lista') }}">
                        {{ __('Principal') }}
                    </a>
                </li>
                @php
                    $perfilId = auth()->check() && auth()->user()->perfil ? auth()->user()->perfil->id_user : (auth()->check() ? auth()->id() : null);
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perfil.ver') ? 'active' : '' }}" href="{{ route('perfil.ver', ['id' => $perfilId]) }}">
                        {{ __('Perfil') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}" href="{{ route('admin') }}">
                        {{ __('Admin') }}
                    </a>
                </li>

                <!-- Dropdown del usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarUsuario" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUsuario">
                        <li>
                            <a class="dropdown-item" href="{{ route('perfil.ver', ['id' => $perfilId]) }}">
                                {{ __('Perfil') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    {{ __('Cerrar sesión') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
