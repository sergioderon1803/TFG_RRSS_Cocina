@extends('layouts.app')

@section('titulo', 'Listado de recetas')

@section('listado')
    
    <p>Aquí aparecerá un listado de todas las recetas</p>

    <x-alerta type="danger">
        Variable slot ($slot es la predeterminada)

        <x-slot name="otraVariable">
            Esta es otra variable
        </x-slot>

        <x-slot name="variableTernaria">
            Variable ternaria manual
        </x-slot>
    </x-alerta>

@endsection