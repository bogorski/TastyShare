@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="fw-bold text-center mb-5">Wszystkie kategorie</h1>

    @if ($categories->isEmpty())
    <p class="text-center">Brak dostępnych kategorii.</p>
    @else
    <div class="row g-4">
        @foreach ($categories as $category)
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('categories.show', $category->id) }}" class="text-decoration-none text-dark d-block h-100">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    @if($category->image_url)
                    <img src="{{ $category->image_url }}" class="card-img-top category-img" alt="{{ $category->name }}">
                    @endif
                    <div class="card-body d-flex justify-content-center align-items-center text-center">
                        <h5 class="card-title mb-0">{{ $category->name }}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection