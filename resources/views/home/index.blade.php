@extends('layouts.app')

@section('content')
<div id="basicCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($categories as $index => $category)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
            <a href="{{ route('categories.show', $category->id) }}" class="text-decoration-none">
                <img src="{{ $category->image_url }}" class="d-block w-100" alt="{{ $category->name }}" style="height: 300px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                    <h5>{{ $category->name }}</h5>
                </div>
            </a>
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