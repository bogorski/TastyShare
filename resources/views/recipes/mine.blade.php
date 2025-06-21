@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Moje przepisy</h1>
        <p class="text-muted">Twoje dodane przepisy</p>
        <a href="{{ route('recipes.create') }}" class="btn btn-success mt-2">Dodaj przepis</a>
    </div>

    @if ($recipes->isEmpty())
    <div class="alert alert-info text-center">Nie dodałeś jeszcze żadnych przepisów.</div>
    @else
    <div class="row g-4">
        @foreach ($recipes as $recipe)
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('recipes.show', $recipe->id) }}" class="text-decoration-none text-dark d-block h-100">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    @if($recipe->image)
                    <img src="{{ $recipe->image }}" class="card-img-top" alt="{{ $recipe->title }}" style="height: 180px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="card-title mb-2">{{ $recipe->title }}</h5>
                        <p class="card-text text-muted small mb-0">{{ Str::limit($recipe->description, 100) }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection