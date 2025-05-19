@extends('layouts.app')

@section('seguidores')
<div class="container mt-4">
    <h2 class="mb-4 text-center"><a href="{{ route('perfil.ver', $perfil->user->id) }}">Seguidores de {{ $perfil->user->id}}</a></h2>

    @if ($seguidores->isEmpty())
        <div class="alert alert-info text-center">
            Este usuario no tiene seguidores.
        </div>
    @else
        <ul class="list-group">
            @foreach ($seguidores as $seguidor)
                <li class="list-group-item d-flex align-items-center">
                    <a href="{{ route('perfil.ver', $seguidor->id) }}" class="text-decoration-none">
                        <strong>{{ $seguidor->perfil->name ?? $seguidor->email }}</strong>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
