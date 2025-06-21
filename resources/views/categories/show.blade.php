@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="fw-bold mb-5 text-center">{{ $category->name }}</h1>

    @if($category->recipes->isEmpty())
    <p class="text-center text-muted">Brak przepisów w tej kategorii.</p>
    @else
    <div class="row g-4">
        @foreach($category->recipes as $recipe)
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('recipes.show', $recipe->id) }}" class="text-decoration-none text-dark d-block h-100">
                <div class="card h-100 shadow-sm border-0 rounded hover-shadow transition">
                    @if($recipe->image)
                    <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" class="card-img-top">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $recipe->title }}</h5>

                        <p class="mb-1">
                            <strong>Średnia ocena:</strong>
                            @php
                            $avgRating = $recipe->ratings_avg_rating ?? null;
                            @endphp
                            {{ $avgRating ? number_format($avgRating, 1) . ' ★' : 'Brak ocen' }}
                        </p>

                        <p class="mb-1"><strong>Czas przygotowania:</strong> {{ $recipe->preparation_time }} minut</p>

                        <p class="card-text text-truncate">
                            <strong>Opis:</strong> {{ $recipe->description }}
                        </p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection