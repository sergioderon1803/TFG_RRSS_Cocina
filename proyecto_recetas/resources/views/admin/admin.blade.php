@extends('layouts.app')

@section('titulo', 'Administración')

@section('admin')

    <div>
        <div class="d-flex justify-content-between mt-3 mx-5 mb-5">
            <form action="{{ route('admin') }}" method="GET" class="d-inline">
                <input type="hidden" name="tipo" value="recetas">
                <input type="submit" id="listaRecetas" class="btn btn-hover-animate fs-2 tamañoBoton" value="Recetas">
                @if(isset($recetas))
                    <script>
                        inputReceta = document.getElementById('listaRecetas');

                        inputReceta.setAttribute('disabled','disabled');
                    </script>
                @endif
            </form>

            <h1 class="titulo">
                {{ request('tipo') == 'usuarios' ? 'Usuarios' : 'Recetas' }}
            </h1>

            <form action="{{ route('admin') }}" method="GET" class="d-inline">
                <input type="hidden" name="tipo" value="usuarios">
                <input type="submit" id="listaUsuarios" class="btn btn-hover-animate fs-2 tamañoBoton" value="Usuarios">
                @if(isset($usuarios))
                    <script>
                        inputUsuario = document.getElementById('listaUsuarios');

                        inputUsuario.setAttribute('disabled','disabled');
                    </script>
                @endif
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
                                    <form class="formBorrar" action="{{ url('usuario/admin/' . $usuario->id) }}" method="POST" style="display:inline-block;">
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
                                    <form action="{{ url('recetas/' . $receta->id . '/editar') }}" method="GET" style="display:inline;">
                                        <button class="btn btn-warning mb-2">Editar</button>
                                    </form>

                                    <form class="formBorrar" action="{{ url('recetas/admin/' . $receta->id) }}" method="POST" style="display:inline-block;">
                                        <div class="modal-body">
                                            @csrf
                                            @method('DELETE')
                                        </div>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
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

{{-- He creado en layouts app un yield js para crear secciones únicamente con javascript --}}

@section('js')

    {{-- Librería de javascript que he importado (sweetAlert2) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        forms = document.querySelectorAll('.formBorrar'); // Cojo todos los formBorrar

        forms.forEach(form => {
            form.addEventListener('submit', (e) =>{
                e.preventDefault(); //Cuándo le den a submit, paro el evento y muestro el pop up

                Swal.fire({
                title: "¿Estás seguro de que deseas borrar este registro?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar"
                }).then((result) => {

                if (result.isConfirmed) { // Si se acepta, se lanza el otro popup y se hace el submit
                    Swal.fire({
                    title: "Registro eliminada",
                    text: "",
                    icon: "success"
                    });
                    
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                }
                });
            })
        })
    </script>
@endsection