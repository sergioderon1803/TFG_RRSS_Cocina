@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h1 class="h3 mb-0 text-primary fw-bold">@yield('page-title')</h1>
                    <div class="border-top border-2 border-primary mt-2" style="width: 50px;"></div>
                </div>
                <div class="card-body p-4">
                    <div class="legal-content">
                        @yield('legal-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
