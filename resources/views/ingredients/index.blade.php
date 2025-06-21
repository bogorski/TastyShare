@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Wszystkie składniki</h1>
        <p class="text-muted">Przeglądaj dostępne składniki i znajdź przepisy w których występują</p>
        @auth
        <a href="{{ route('ingredients.create') }}" class="btn btn-success mt-2">Dodaj składnik</a>
        @endauth
    </div>

    @if ($ingredients->isEmpty())
    <div class="alert alert-info text-center">Brak dostępnych składników.</div>
    @else
    <div class="row g-4">
        @foreach ($ingredients as $ingredient)
        <div class="col-12 col-sm-6 col-lg-2">
            <a href="{{ route('ingredients.show', $ingredient->id) }}" class="text-decoration-none text-dark h-100 d-block">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                        <h5 class="card-title mb-0">{{ $ingredient->name }}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection