@extends('layouts.app')

@section('titulo', 'Administración')

@section('css')

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
@endsection

@section('admin')


<div class="container py-5">
    <div class="row align-items-center justify-content-between">
        <!-- Botón Recetas -->
        <div class="col-12 col-md-4 d-flex justify-content-center mb-4 mb-md-0">
            <a href="{{ url('admin/recetas') }}" class="btn btn-warning btn-lg px-4 shadow-sm fs-4 btn-hover-animate">
                Recetas
            </a>
        </div>

        <!-- Título central -->
        <div class="col-12 col-md-4 text-center">
            <h1 class="fw-bold display-5 mb-0">Elija el listado</h1>
        </div>

        <!-- Botón Usuarios -->
        <div class="col-12 col-md-4 d-flex justify-content-center mb-4 mb-md-0">
            <a href="{{ url('admin/usuarios') }}" class="btn btn-warning btn-lg px-4 shadow-sm fs-4 btn-hover-animate">
                Usuarios
            </a>
        </div>

    </div>
</div>


{{-- Tabla Usuarios --}}
<table class="table table-bordered align-middle text-center" id="usuarios">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Fecha de registro</th>
        </tr>
    </thead>
</table>

@endsection

@section('js')

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <script>
        /*$(document).ready(function(){
            $.ajax({
                url: 'admin/usuariosAjax',
                method: 'POST',
                data: {
                    _token:$('input[name="_token"]').val()
                }
            }).done(function(res){
                var arreglo = JSON.parse(res);

                for(var x = 0 ; x < arreglo['data'].length ; x++){

                    let date = new Date(arreglo['data'][x].created_at)


                    var todo ='<tr><td>' + arreglo['data'][x].id + '</td>';
                    todo += '<td>' + arreglo['data'][x].email + '</td>';
                    todo += '<td>' + arreglo['data'][x].user_type + '</td>';
                    todo += '<td>' + date.toLocaleDateString("en-US") + '</td>';
                    todo += '<td></td></tr>';
                    $('tbody').append(todo);
                }
            });
        });*/

        var tablaUsuarios = new DataTable('#usuarios', {
            responsive: true,
            
            "ajax": "{{route('admin.usuariosAjax')}}",
            "columns":[
                {data: 'id' , name: 'Id'},
                {data: 'email', name: 'Email'},
                {data: 'user_type', name: 'Rol'},
                {data: 'created_at', name: 'Fecha Creación'},
                {data: 'action', name: 'Acciones', orderable: false, searchable: false}
            ],
            "language": {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 de 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
          }
        });

        $('table').on('click', '.delete-user',function(){
            const userId = $(this).data('id');
            
            if(userId){
                Swal.fire({
                title: '¿Estás seguro de que deseas borrar este usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar'
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('usuario/admin/') }}/${userId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{csrf_token()}}',
                        },

                        success: function(response){
                            if(response.status === 'success'){
                                Swal.fire('Usuario borrado', '', 'success');
                                tablaUsuarios.ajax.reload(null,true);
                            }else{
                                Swal.fire('No se ha podido completar la solicitud', '', 'warning');
                            }
                        },
                        error: function(error){
                            Swal.fire('Se ha producido un error', '', 'error');
                        }
                    })
                }
            });
            }
        })
    </script>
@endsection