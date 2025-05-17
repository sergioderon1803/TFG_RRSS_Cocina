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
                {{ request('tipo') == 'usuarios' ? 'Usuarios' : 'Recetas' }}
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
        <div class="mx-5 mt-5">
            <table class="table table-bordered">
                @if(request('tipo') === 'usuarios')
                <thead>                   
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha de registro</th>
                        <th></th>
                    </tr>
                </thead>
                    {{-- Tabla de Usuarios --}}
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->id }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->user_type }}</td>
                                <td>{{ $usuario->created_at ?? 'N/D' }}</td>
                                <td class="text-center">
                                    <form action="{{ url('usuario/admin/' . $usuario->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                @else

                <thead>                   
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Autor</th>
                        <th>Fecha de creación</th>
                        <th></th>
                    </tr>
                </thead>
                   {{-- Tabla de Recetas --}}
                    <tbody>
                        @forelse($recetas as $receta)
                            <tr>
                                <td>{{ $receta->id }}</td>
                                <td><a href="{{ url('receta/' . $receta->id) }}" class="btn btn-primary btn-sm">{{ $receta->titulo }}</a></td>
                                <td>{{ $receta->tipo }}</td>
                                <td>{{ $receta->autor->email ?? 'Desconocido' }}</td>
                                <td>{{ $receta->created_at ?? 'N/D' }}</td>
                                <td class="text-center">
                                    <form action="{{ url('recetas/admin/' . $receta->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                    <form action="{{ url('recetas/' . $receta->id . '/editar') }}" method="GET" style="display:inline;">
                                        <button class="btn btn-warning mb-2">Editar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No hay recetas disponibles.</td>
                            </tr>
                        @endforelse
                @endif
            </table>
            
            {{-- Paginación --}}
            @if(request('tipo') === 'usuarios')
                {{ $usuarios->links() }}
            @else
                <a href="{{ url('recetas/crear') }}" class="btn btn-primary">Nueva receta</a>
                {{ $recetas->links() }}
            @endif
        </div>
    </div>

@endsection