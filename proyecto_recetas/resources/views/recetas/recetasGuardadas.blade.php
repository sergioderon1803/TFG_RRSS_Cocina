@extends('layouts.app')

@section('titulo', 'Recetas Guardadas')

@section('content')
    <i class="bi bi-bookmark-fill fs-5" style="color: #2A9D8F;"></i>
    <i class="bi bi-bookmark-fill fs-5" style="color: #2A9D8F;"></i>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function(){
            $("i").on("click", function(){
                if($(this).hasClass('bi-bookmark-fill')){

                    $(this).removeClass('bi-bookmark-fill');
                    $(this).addClass('bi-bookmark');

                }
                else
                {
                    $(this).removeClass('bi-bookmark');
                    $(this).addClass('bi-bookmark-fill');
                }
                
            });
        });
    </script>

@endsection