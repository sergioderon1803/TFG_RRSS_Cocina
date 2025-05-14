@extends('layouts.app')

@section('titulo', 'Administración')

@section('admin')
    <div>
        <div class="d-flex justify-content-between mt-3 mx-5 mb-5">
            <form action="{{ route('admin') }}" method="GET" class="d-inline">
                <input type="hidden" name="tipo" value="recetas">
                <input type="submit" class="btn btn-hover-animate fs-2 tamañoBoton" value="Recetas">
            </form>

            <h1 class="titulo">
                {{ request('tipo') === 'usuarios' ? 'Usuarios' : 'Recetas' }}
            </h1>

            <form action="{{ route('admin') }}" method="GET" class="d-inline">
                <input type="hidden" name="tipo" value="usuarios">
                <input type="submit" class="btn btn-hover-animate fs-2 tamañoBoton" value="Usuarios">
            </form>
        </div>

        {{-- Filtros: Aún sin funcionalidad backend --}}
        <div class="mx-2 d-flex justify-content-center">
            <form action="" method="post">
                @csrf
                <input type="text" id="id" class="mx-1" placeholder="Id">
                <input type="text" id="alias" class="mx-1" placeholder="Alias">
                <input type="text" id="username" class="mx-1" placeholder="@Username">
                <input type="text" id="fechaCreacion" class="mx-1" placeholder="F_Creación">
                <input type="submit" class="btn mx-1 bg-primary" value="Filtrar">
            </form>
        </div>

        {{-- Mostrar tabla correspondiente --}}
        @if(request('tipo') === 'usuarios')
            {{-- Tabla de Usuarios --}}
            <div class="mx-5 mt-5">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Fecha de registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->id }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->user_type }}</td>
                                <td>{{ $usuario->created_at ?? 'N/D' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $usuarios->links() }}
            </div>
        @else
            {{-- Tabla de Recetas --}}
            <div class="mx-5 mt-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Autor</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recetas as $receta)
                            <tr>
                                <td>{{ $receta->id }}</td>
                                <td>{{ $receta->titulo }}</td>
                                <td>{{ $receta->tipo }}</td>
                                <td>{{ $receta->autor->email ?? 'Desconocido' }}</td>
                                <td>{{ $receta->created_at ?? 'N/D' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No hay recetas disponibles.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $recetas->links() }}
            </div>
        @endif
    </div>
@endsection