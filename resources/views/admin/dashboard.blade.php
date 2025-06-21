@extends('layouts.app')

@admin
@section('content')
<div class="container my-5">
    <h1 class="text-center mb-5" style="color: var(--primary-color); font-weight: 700;">Panel Administracyjny</h1>
    <p class="text-center mb-4 text-muted fs-5">Wybierz sekcję do zarządzania</p>

    <div class="row g-4 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('admin.users.index') }}" class="card shadow-sm h-100 text-center text-decoration-none text-dark">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Użytkownicy</h5>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('admin.recipes.index') }}" class="card shadow-sm h-100 text-center text-decoration-none text-dark">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Przepisy</h5>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('admin.comments.index') }}" class="card shadow-sm h-100 text-center text-decoration-none text-dark">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Komentarze</h5>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('admin.categories.index') }}" class="card shadow-sm h-100 text-center text-decoration-none text-dark">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Kategorie</h5>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('admin.dietTypes.index') }}" class="card shadow-sm h-100 text-center text-decoration-none text-dark">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Rodzaje diet</h5>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('admin.ingredients.index') }}" class="card shadow-sm h-100 text-center text-decoration-none text-dark">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Składniki</h5>
                </div>
            </a>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Powrót do strony głównej</a>
    </div>
</div>
@endsection
@endadmin