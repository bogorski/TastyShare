@extends('layouts.app')

@section('title', 'Wszystkie przepisy')

@section('content')
<div class="container">
    <h1 class="mb-4">Wszystkie przepisy
        <small class="text-muted fs-6">({{ $recipes->total() }} wyników)</small>
    </h1>
    <div class="mb-4">
        {{-- Formularz sortowania --}}
        <form method="GET" action="{{ route('recipes.index') }}" class="row g-2 align-items-center mb-3">
            {{-- Zachowaj parametr wyszukiwania --}}
            <input type="hidden" name="search" value="{{ request('search') }}">

            <div class="col-auto">
                <label for="sort" class="col-form-label">Sortuj według:</label>
            </div>
            <div class="col-auto">
                <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                    <option value="created_at" {{ $sort == 'created_at' ? 'selected' : '' }}>Data dodania</option>
                    <option value="title" {{ $sort == 'title' ? 'selected' : '' }}>Tytuł</option>
                    <option value="preparation_time" {{ $sort == 'preparation_time' ? 'selected' : '' }}>Czas przygotowania</option>
                    <option value="average_rating" {{ $sort == 'average_rating' ? 'selected' : '' }}>Średnia ocena</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="order" class="form-select" onchange="this.form.submit()">
                    <option value="asc" {{ $order == 'asc' ? 'selected' : '' }}>Rosnąco</option>
                    <option value="desc" {{ $order == 'desc' ? 'selected' : '' }}>Malejąco</option>
                </select>
            </div>
        </form>

        {{-- Formularz wyszukiwania --}}
        <div class="mb-4">
            <form method="GET" action="{{ route('recipes.index') }}" class="row g-2 align-items-center">
                {{-- Zachowaj parametry sortowania i wyszukiwania --}}
                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="order" value="{{ $order }}">

                <div class="col-auto">
                    <input type="text" name="search" class="form-control" placeholder="Szukaj przepisu..." value="{{ request('search') }}">
                </div>

                <div class="col-auto">
                    <select name="category_id" class="form-select">
                        <option value="">Wszystkie kategorie</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <select name="diettype_id" class="form-select">
                        <option value="">Wszystkie diety</option>
                        @foreach($diettypes as $diettype)
                        <option value="{{ $diettype->id }}" {{ request('diettype_id') == $diettype->id ? 'selected' : '' }}>
                            {{ $diettype->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <select name="max_preparation_time" class="form-select">
                        <option value="">Dowolny czas przygotowania</option>
                        <option value="15" {{ request('max_preparation_time') == '15' ? 'selected' : '' }}>Do 15 min</option>
                        <option value="30" {{ request('max_preparation_time') == '30' ? 'selected' : '' }}>Do 30 min</option>
                        <option value="60" {{ request('max_preparation_time') == '60' ? 'selected' : '' }}>Do 60 min</option>
                    </select>
                </div>

                <div class="col-auto">
                    <select name="min_rating" class="form-select">
                        <option value="">Wszystkie oceny</option>
                        <option value="1" {{ request('min_rating') == '1' ? 'selected' : '' }}>1 ★ i więcej</option>
                        <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2 ★ i więcej</option>
                        <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3 ★ i więcej</option>
                        <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4 ★ i więcej</option>
                        <option value="5" {{ request('min_rating') == '5' ? 'selected' : '' }}>5 ★</option>
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filtruj</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">Wyczyść filtry</a>
                </div>
            </form>
        </div>
    </div>
    @if($recipes->isEmpty())
    <p>Brak przepisów do wyświetlenia.</p>
    @else
    <div class="row">
        @foreach ($recipes as $recipe)
        <div class="col-12 col-md-4 mb-3">
            <div class="card h-100">
                @if($recipe->image)
                <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $recipe->title }}</h5>

                    <p class="mb-1">
                        <strong>Średnia ocena:</strong>
                        @php
                        $avgRating = $recipe->ratings_avg_rating;
                        @endphp
                        {{ $avgRating ? number_format($avgRating, 1) . ' ★' : 'Brak ocen' }}
                    </p>

                    <p class="mb-1"><strong>Czas przygotowania:</strong> {{ $recipe->preparation_time }} minut</p>

                    <p class="mb-3">
                        <strong>Opis:</strong> {{ $recipe->description }}
                    </p>
                    <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-primary mt-auto">Zobacz przepis</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    {{-- Paginacja --}}
    <div class="mt-4">
        {{ $recipes->links() }}
    </div>
    @endif
</div>
@endsection