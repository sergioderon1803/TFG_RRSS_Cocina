@extends('layouts.app')

@section('titulo', 'Admin Recetas')

@section('adminRecetas')

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
        <a href="{{ url('admin/recetas') }}" class="btn btn-warning btn-lg btn-hover-animate px-4 fs-4 disabled">Recetas</a>
        <h1 class="fw-bold display-5 text-center flex-grow-1">Recetas</h1>
        <a href="{{ url('admin/usuarios') }}" class="btn btn-warning btn-lg btn-hover-animate px-4 fs-4">Usuarios</a>
    </div>

    {{-- Filtros --}}
    <form action="" method="post" class="d-flex justify-content-center gap-2 flex-wrap mb-4">
        @csrf
        <input type="text" name="id" class="form-control form-control-sm" placeholder="Id" style="max-width: 100px;">
        <input type="text" name="alias" class="form-control form-control-sm" placeholder="Alias" style="max-width: 150px;">
        <input type="text" name="username" class="form-control form-control-sm" placeholder="@Username" style="max-width: 150px;">
        <input type="text" name="fechaCreacion" class="form-control form-control-sm" placeholder="F_Creación" style="max-width: 130px;">
        <button type="submit" class="btn btn-primary btn-sm px-4">Filtrar</button>
    </form>

    {{-- Tabla Recetas --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Autor</th>
                    <th>Fecha de creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recetas as $receta)
                    <tr>
                        <td>{{ $receta->id }}</td>
                        <td><a href="{{ url('receta/' . $receta->id) }}" class="btn btn-primary btn-sm">{{ $receta->titulo }}</a></td>
                        <td>{{ $receta->tipo }}</td>
                        <td>{{ $receta->autor->email }}</td>
                        <td>{{ $receta->created_at ?? 'N/D' }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <form action="{{ url('recetas/' . $receta->id . '/editar') }}" method="GET" style="display:inline;">
                                    <button class="btn btn-warning btn-sm px-3">Editar</button>
                                </form>

                                <form class="formBorrar" action="{{ url('recetas/admin/' . $receta->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm px-3">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No hay recetas disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
        <a href="{{ url('recetas/crear') }}" class="btn btn-success btn-lg px-4">Nueva receta</a>
        @if ($recetas->hasPages())
            <div>{{ $recetas->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.formBorrar').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro de que deseas borrar esta receta?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar'
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire('Receta eliminada', '', 'success');
                    setTimeout(() => form.submit(), 500);
                }
            });
        });
    });
</script>
@endsection