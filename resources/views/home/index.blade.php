@extends('layouts.app')

@section('content')
@admin
<p>To widzi tylko admin!</p>
@endadmin
@if(session('success'))
<div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<script>
    // Po 5 sekundach ukryj alert
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            // Bootstrap 5 - usuń klasę 'show' żeby zacząć animację znikania
            alert.classList.remove('show');
            // Po animacji usuń element z DOM
            setTimeout(() => alert.remove(), 150);
        }
    }, 5000);
</script>
@endif
<h2 class="mb-4 text-center">Najnowsze przepisy</h2>
<div id="basicCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($latestRecipes->chunk(3) as $index => $recipeChunk)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
            <div class="row">
                @foreach($recipeChunk as $recipe)
                <div class="col-12 col-md-4 mb-3">
                    <a href="{{ route('recipes.show', $recipe->id) }}" class="text-decoration-none">
                        <div class="card h-100 text-white position-relative" style="height: 250px; overflow: hidden;">
                            @if($recipe->image)
                            <img src="{{ $recipe->image }}" class="card-img" alt="{{ $recipe->title }}" style="object-fit: cover; height: 250px;">
                            @endif
                            <div class="card-img-overlay d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.4);">
                                <h5 class="card-title text-center">{{ $recipe->title }}</h5>
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

<div class="container">
    <h1 class="mb-4">Popularne przepisy</h1>
    <div class="row">
        @foreach($popularRecipes as $recipe)
        <div class="col-12 col-md-4 mb-3">
            <div class="card h-100">
                @if($recipe->image)
                <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                @else
                <img src="{{ asset('image/placeholder.png') }}" alt="Brak zdjęcia" class="card-img-top" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $recipe->title }}</h5>
                    <p class="mb-1 text-muted">Dodany przez: {{ $recipe->user->name ?? 'Anonim' }}</p>
                    <p class="mb-2">
                        Średnia ocena:
                        {{ number_format($recipe->ratings_avg_rating, 1) }} ★
                    </p>
                    <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary mt-auto">Zobacz przepis</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="container my-4">
    <h2 class="mb-4 text-center">Najnowsze komentarze</h2>
    <div class="row">
        @foreach($latestComments as $comment)
        <div class="col-12 col-md-4 mb-3">
            <div class="card h-100">
                @if($comment->recipe->image)
                <img src="{{ $comment->recipe->image }}" class="card-img-top" alt="{{ $comment->recipe->title }}" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $comment->user->name ?? 'Anonim' }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $comment->created_at->diffForHumans() }}</h6>
                    <p class="card-text flex-grow-1">{{ Str::limit($comment->comment, 150) }}</p>
                    <a href="{{ route('recipes.show', $comment->recipe->id) }}" class="btn btn-primary mt-auto">Zobacz przepis</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection