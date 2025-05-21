@extends('layouts.app')

@section('titulo', 'Admin Usuarios')

@section('adminUsuarios')

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
        <a href="{{ url('admin/recetas') }}" class="btn btn-warning btn-lg btn-hover-animate px-4 fs-4">Recetas</a>
        <h1 class="fw-bold display-5 text-center flex-grow-1">Usuarios</h1>
        <a href="{{ url('admin/usuarios') }}" class="btn btn-warning btn-lg btn-hover-animate px-4 fs-4 disabled">Usuarios</a>
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

    {{-- Tabla Usuarios --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha de registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->user_type }}</td>
                        <td>{{ $usuario->created_at ?? 'N/D' }}</td>
                        <td>
                            <form class="formBorrar" action="{{ url('usuario/admin/' . $usuario->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm px-3">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        @if ($usuarios->hasPages())
            <div>{{ $usuarios->links('pagination::bootstrap-5') }}</div>
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
                title: '¿Estás seguro de que deseas borrar este usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar'
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire('Usuario borrado', '', 'success');
                    setTimeout(() => form.submit(), 500);
                }
            });
        });
    });
</script>
@endsection