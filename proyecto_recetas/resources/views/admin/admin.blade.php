@extends('layouts.app')

@section('titulo', 'Administración')

@section('css')
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
@endsection

@section('content')


<div class="container py-5">
    <div class="row align-items-center justify-content-between">
        <!-- Botón Recetas -->
        <div class="col-12 col-md-4 d-flex justify-content-center mb-4 mb-md-0">
            <button id="botonRecetas" class="btn btn-warning btn-lg px-4 shadow-sm fs-4 btn-hover-animate">Recetas</button>
        </div>

        <!-- Título central -->
        <div class="col-12 col-md-4 text-center">
            <h1 id="tituloAdmin" class="fw-bold display-5 mb-0">Elija el listado</h1>
        </div>

        <!-- Botón Usuarios -->
        <div class="col-12 col-md-4 d-flex justify-content-center mb-4 mb-md-0">
            <button id="botonUsuarios" class="btn btn-warning btn-lg px-4 shadow-sm fs-4 btn-hover-animate">Usuarios</button>
        </div>

    </div>
</div>


<table class="table table-bordered align-middle text-center" id="tabla">
    <thead class="table-light" id="cabecera" hidden>
        <tr>
            <th id="1"></th>
            <th id="2"></th>
            <th id="3"></th>
            <th id="4"></th>
            <th id="5"></th>
        </tr>
    </thead>
</table>

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <script>

        // Al principio, tienen sus eventos los dos botones
        document.getElementById("botonUsuarios").addEventListener('click',crearTablaUsuarios);
        document.getElementById("botonRecetas").addEventListener('click',crearTablaReceta);

        let eventos = true;


        function crearTablaReceta(){

            $('#tituloAdmin').text("Recetas");

            // Les quito sus eventos si no se los he quitado ya
            if(eventos){
                eventos = false;
                document.getElementById("botonUsuarios").removeEventListener('click',crearTablaUsuarios);
                document.getElementById("botonRecetas").removeEventListener('click',crearTablaReceta);
            }

            // Deshabilito el botón y nombro sus headers
            document.getElementById("botonRecetas").setAttribute('disabled','disabled');
            document.getElementById("cabecera").removeAttribute('hidden');

            document.getElementById("1").innerHTML = "ID";
            document.getElementById("2").innerHTML = "Título";
            document.getElementById("3").innerHTML = "Tipo";
            document.getElementById("4").innerHTML = "Autor";
            document.getElementById("5").innerHTML = "Fecha Creación";


            // Creo el datatable
            var tablaRecetas = new DataTable('#tabla', {
                responsive: true,
                
                "ajax": "{{route('admin.recetasAjax')}}", // Ruta del controlador de dónde los cojo

                // Nombre de los parámetros que irán en las columnas
                "columns":[
                    {data: 'id' , name: 'Id'},
                    {data: 'titulo', name: 'Titulo'},
                    {data: 'tipo', name: 'Tipo'},
                    {data: 'autor_receta', name: 'Autor'},
                    {data: 'created_at', name: 'Fecha Creación'},
                    {data: 'action', name: 'Acciones', orderable: false, searchable: false} // Para que no se pueda ordenar por él ni buscar
                ],
                // Traducción de la tabla
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

            //Ahora los voy a gestionar desde aquí lo que hacen los botones

            $('#botonUsuarios').on('click',function(){
                tablaRecetas.destroy(); // Destruyo la tabla
                $('#tabla').empty(); // Los datos se quedan, por eso hay que borrarla

                $('#botonUsuarios').off('click'); // Le quito este evento

                // Habilito el otro botón y deshabilito el pulsado
                document.getElementById("botonRecetas").removeAttribute('disabled');
                document.getElementById("botonUsuarios").setAttribute('disabled','disabled');

                // Necesito crear la cabecera de la tabla, si no, no funciona el datatable

                var cabecera = `<thead class="table-light" id="cabecera" hidden>
                                    <tr>
                                        <th id="1"></th>
                                        <th id="2"></th>
                                        <th id="3"></th>
                                        <th id="4"></th>
                                        <th id="5"></th>
                                    </tr>
                                </thead>`;
                
                $('#tabla').append(cabecera);

                crearTablaUsuarios(); // Llamo a la función que la crea

            });

            // Me refiero a la clase del botón de borrar

            $('table').on('click', '.delete-receta',function(){
                const recetaId = $(this).data('id');
                
                if(recetaId){
                    Swal.fire({
                    title: '¿Estás seguro de que deseas borrar esta receta?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Eliminar'
                }).then(result => {
                    if (result.isConfirmed) { // Si acepta borrarla, hago un ajax
                        $.ajax({
                            url: `{{ url('recetas/admin/') }}/${recetaId}`, // Llamo al controlador y le paso el ID
                            method: 'DELETE',
                            data: {
                                _token: '{{csrf_token()}}', // Le paso el token de la sesión, si no, no me deja hacerlo
                            },
                            
                            // Si acepta y la respuesta es la que mando en el controlador, lanzo un pop up
                            success: function(response){
                                if(response.status === 'success'){
                                    Swal.fire('Receta borrada', '', 'success');
                                    tablaRecetas.ajax.reload(null,true); // Recarga el ajax de la tabla
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
        }

        // Exactamente el mismo funcionamiento pero al revés
        function crearTablaUsuarios(){

            $('#tituloAdmin').text("Usuarios");

            // Les quito sus eventos
            if(eventos){
                eventos = false;
                document.getElementById("botonUsuarios").removeEventListener('click',crearTablaUsuarios);
                document.getElementById("botonRecetas").removeEventListener('click',crearTablaReceta);
            }

            document.getElementById("botonUsuarios").setAttribute('disabled','disabled');
            document.getElementById("cabecera").removeAttribute('hidden');

            document.getElementById("1").innerHTML = "ID";
            document.getElementById("2").innerHTML = "Email";
            document.getElementById("3").innerHTML = "Rol";
            document.getElementById("4").innerHTML = "Fecha";
            document.getElementById("5").innerHTML = "Acciones";


            var tablaUsuarios = new DataTable('#tabla', {
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

            //Ahora los voy a gestionar desde aquí

            $('#botonRecetas').on('click',function(){
                tablaUsuarios.destroy();
                $('#tabla').empty();
                $('#botonRecetas').off('click');
                document.getElementById("botonUsuarios").removeAttribute('disabled');
                document.getElementById("botonRecetas").setAttribute('disabled','disabled');

                var cabecera = `<thead class="table-light" id="cabecera" hidden>
                                    <tr>
                                        <th id="1"></th>
                                        <th id="2"></th>
                                        <th id="3"></th>
                                        <th id="4"></th>
                                        <th id="5"></th>
                                    </tr>
                                </thead>`;
                
                $('#tabla').append(cabecera);

                crearTablaReceta();

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
        }

        
    </script>
@endsection