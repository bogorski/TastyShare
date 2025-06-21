@extends('layouts.app')

@section('content')

<div class="container my-5 moje">

    <h2 class="mb-4 text-center">Najnowsze przepisy</h2>

    <div id="basicCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
        <div class="carousel-inner">
            @foreach($latestRecipes->chunk(3) as $index => $recipeChunk)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <div class="row g-3 justify-content-center">
                    @foreach($recipeChunk as $recipe)
                    <div class="col-12 col-md-4">
                        <a href="{{ route('recipes.show', $recipe->id) }}" class="text-decoration-none">
                            <div class="card shadow-sm h-100 text-white position-relative overflow-hidden" style="height: 250px;">
                                @if($recipe->image)
                                <img src="{{ $recipe->image }}" class="card-img" alt="{{ $recipe->title }}" style="object-fit: cover; height: 250px;">
                                @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <span class="text-white fs-4">Brak zdjęcia</span>
                                </div>
                                @endif
                                <div class="card-img-overlay d-flex justify-content-center align-items-center"
                                    style="background: rgba(0, 0, 0, 0.45);">
                                    <h5 class="card-title text-center fw-bold text-truncate" title="{{ $recipe->title }}">
                                        {{ $recipe->title }}
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#basicCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Poprzedni</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#basicCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Następny</span>
        </button>
    </div>

    <hr class="my-5">

    <h1 class="mb-4 text-center">Popularne przepisy</h1>

    <div class="row g-4">
        @foreach($popularRecipes as $recipe)
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                @if($recipe->image)
                <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                @else
                <img src="{{ asset('image/placeholder.png') }}" alt="Brak zdjęcia" class="card-img-top" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate" title="{{ $recipe->title }}">{{ $recipe->title }}</h5>
                    <p class="mb-1 text-muted small">Dodany przez: {{ $recipe->user->name ?? 'Anonim' }}</p>
                    <p class="mb-3 fw-semibold">
                        Średnia ocena: <span class="text-warning">{{ number_format($recipe->ratings_avg_rating, 1) }} ★</span>
                    </p>
                    <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary mt-auto">Zobacz przepis</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <hr class="my-5">

    <h2 class="mb-4 text-center">Najnowsze komentarze</h2>

    <div class="row g-4">
        @foreach($latestComments as $comment)
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                @if($comment->recipe->image)
                <img src="{{ $comment->recipe->image }}" class="card-img-top" alt="{{ $comment->recipe->title }}" style="height: 180px; object-fit: cover;">
                @else
                <div class="bg-secondary" style="height: 180px;"></div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $comment->user->name ?? 'Anonim' }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted small">{{ $comment->created_at->diffForHumans() }}</h6>
                    <p class="card-text flex-grow-1">{{ Str::limit($comment->comment, 150) }}</p>
                    <a href="{{ route('recipes.show', $comment->recipe->id) }}" class="btn btn-primary mt-auto">Zobacz przepis</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

@endsection