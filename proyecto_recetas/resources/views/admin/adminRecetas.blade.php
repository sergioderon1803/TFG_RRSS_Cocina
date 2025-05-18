@extends('layouts.app')

@section('titulo', 'Admin Recetas')

@section('adminRecetas')


    <div>
        <div class="d-flex justify-content-between mt-3 mx-5 mb-5">
            <a href="{{ url('admin/recetas') }}" class="btn btn-hover-animate fs-2 tamañoBoton">Recetas</a>
            <h1 class="titulo">Recetas</h1>
            <a href="{{ url('admin/usuarios') }}" class="btn btn-hover-animate fs-2 tamañoBoton">Usuarios</a>
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
            </table>
            
            {{-- Paginación --}}
            <a href="{{ url('recetas/crear') }}" class="btn btn-primary">Nueva receta</a>
            {{ $recetas->links() }}
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
                title: "¿Estás seguro de que deseas borrar esta receta?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar"
                }).then((result) => {

                if (result.isConfirmed) { // Si se acepta, se lanza el otro popup y se hace el submit
                    Swal.fire({
                    title: "Receta eliminada",
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