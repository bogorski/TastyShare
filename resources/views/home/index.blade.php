@extends('layouts.app')

@section('content')

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

<div id="basicCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($latestRecipes->chunk(3) as $index => $recipeChunk)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
            <div class="row">
                @foreach($recipeChunk as $recipe)
                <div class="col-12 col-md-4 mb-3">
                    <a href="{{ route('recipes.show', $recipe->id) }}" class="text-decoration-none">
                        <div class="card h-100 text-white position-relative" style="height: 150px; overflow: hidden;">
                            @if($recipe->image)
                            <img src="{{ $recipe->image }}" class="card-img" alt="{{ $recipe->title }}" style="object-fit: cover; height: 150px;">
                            @else
                            <img src="{{ asset('images/default-recipe.jpg') }}" class="card-img" alt="{{ $recipe->title }}" style="object-fit: cover; height: 150px;">
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
    @foreach($categories->chunk(3) as $chunkIndex => $categoryChunk)
    <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
        <div class="row justify-content-center">
            @foreach($categoryChunk as $category)
            <div class="col-12 col-md-4 mb-3">
                <a href="{{ route('categories.show', $category->id) }}" class="text-decoration-none">
                    <div class="card h-100 text-white position-relative" style="height: 150px; overflow: hidden;">
                        <img src="{{ $category->image_url }}" class="card-img" alt="{{ $category->name }}" style="object-fit: cover; height: 150px;">
                        <div class="card-img-overlay d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.4);">
                            <h5 class="card-title text-center">{{ $category->name }}</h5>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>


@endsection