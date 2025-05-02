@props(['type' => 'info'])

@php
    switch ($type) {
        case 'info':
            $color = 'color: green';
            break;
        case 'danger':
            $color = 'color: red';
            break;
        case 'alert':
            $color = 'color: yellow';
            break;
        default:
            $color = 'color: black';
    }
@endphp

<div style = "{{ $color }}">
    <p>Esto es un COMPONENTE variable -> <strong>{{ $slot }}</strong></p>
    <p>Podemos añadir múltiples variables en un componente -> <strong>{{ $otraVariable }}</strong></p>
    <p>Podemos añadir variables ternarias a un componente -> <strong>{{ $variableTernaria ?? 'Variable ternaria predefinida'}}</strong></p>
</div>