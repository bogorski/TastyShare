@extends('layouts.app')

@section('title', 'Wszystkie kategorie')

@section('content')
<div class="container">
    <h2 class="mb-4">Wszystkie kategorie</h2>

    @if ($categories->isEmpty())
    <p>Brak dostępnych kategorii.</p>
    @else
    <div class="row">
        @foreach ($categories as $category)
        <div class="col-12 col-md-4 mb-3">
            <div class="card h-100">
                @if($category->image_url)
                <img src="{{ $category->image_url }}" class="card-img-top" alt="{{ $category->name }}" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <a href="{{ route('categories.show', $category->id) }}" class="btn btn-primary">Zobacz przepisy</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection