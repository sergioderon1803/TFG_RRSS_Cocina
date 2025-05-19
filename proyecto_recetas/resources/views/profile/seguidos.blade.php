@extends('layouts.app')

@section('seguidores')
<div class="container mt-4">
    <h2 class="mb-4 text-center"><a href="{{ route('perfil.ver', $perfil->user->id) }}">Seguidos de {{ $perfil->user->id}}</a></h2>

    @if ($seguidos->isEmpty())
        <div class="alert alert-info text-center">
            Este usuario no sigue a nadie.
        </div>
    @else
        <ul class="list-group">
            @foreach ($seguidos as $seguido)
                <li class="list-group-item d-flex align-items-center">
                    <a href="{{ route('perfil.ver', $seguido->id) }}" class="text-decoration-none">
                        <strong>{{ $seguido->perfil->name ?? $seguido->email }}</strong>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
