@extends('layouts.app')

@section('title', 'Moje przepisy')

@section('content')
<div class="container">
    <h1 class="mb-4">Moje przepisy</h1>

    @if ($recipes->isEmpty())
    <p>Nie dodałeś jeszcze żadnych przepisów.</p>
    @else
    <div class="row">
        @foreach ($recipes as $recipe)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                @if($recipe->image)
                <img src="{{ $recipe->image }}" class="card-img-top" alt="{{ $recipe->title }}" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $recipe->title }}</h5>
                    <p class="card-text">{{ Str::limit($recipe->description, 100) }}</p>
                    <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary">Zobacz przepis</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection