@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $category->name }}</h1>
    @if($category->image_url)
    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid mb-4" style="max-height: 300px; object-fit: cover;">
    @endif

    <h3>Przepisy w kategorii {{ $category->name }}</h3>

    @if($category->recipes->isEmpty())
    <p>Brak przepisów w tej kategorii.</p>
    @else
    <div class="row">
        @foreach($category->recipes as $recipe)
        <div class="col-12 col-md-4 mb-3">
            <div class="card h-100">
                @if($recipe->image_url)
                <img src="{{ $recipe->image_url }}" class="card-img-top" alt="{{ $recipe->title }}" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $recipe->title }}</h5>
                    <a href="/recipes/{{ $recipe->id }}" class="btn btn-primary">Zobacz przepis</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection