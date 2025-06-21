@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-1">Wszystkie przepisy</h1>
        <div class="text-muted fs-6">({{ $recipes->total() }} wyników)</div>
    </div>

    {{-- Filtry i wyszukiwanie --}}
    <form method="GET" action="{{ route('recipes.index') }}" class="row g-3 align-items-end mb-5">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="order" value="{{ $order }}">
        <div class="col-md-4">
            <label for="search" class="form-label">Szukaj przepisu</label>
            <input type="text" id="search" name="search" class="form-control" placeholder="Szukaj przepisu..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label for="category_id" class="form-label">Kategoria</label>
            <select name="category_id" id="category_id" class="form-select">
                <option value="">Wszystkie kategorie</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="diettype_id" class="form-label">Dieta</label>
            <select name="diettype_id" id="diettype_id" class="form-select">
                <option value="">Wszystkie diety</option>
                @foreach($diettypes as $diettype)
                <option value="{{ $diettype->id }}" {{ request('diettype_id') == $diettype->id ? 'selected' : '' }}>
                    {{ $diettype->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="max_preparation_time" class="form-label">Maks. czas (min)</label>
            <select name="max_preparation_time" id="max_preparation_time" class="form-select">
                <option value="">Dowolny czas przygotowania</option>
                <option value="15" {{ request('max_preparation_time') == '15' ? 'selected' : '' }}>Do 15 min</option>
                <option value="30" {{ request('max_preparation_time') == '30' ? 'selected' : '' }}>Do 30 min</option>
                <option value="60" {{ request('max_preparation_time') == '60' ? 'selected' : '' }}>Do 60 min</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="min_rating" class="form-label">Minimalna ocena</label>
            <select name="min_rating" id="min_rating" class="form-select">
                <option value="">Wszystkie oceny</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ request('min_rating') == $i ? 'selected' : '' }}>
                    {{ $i }} ★ i więcej
                    </option>
                    @endfor
            </select>
        </div>
        <div class="d-flex justify-content-between align-items-center gap-3 mb-4 flex-wrap">
            <div class="d-flex gap-2 flex-shrink-0">
                <button type="submit" class="btn btn-primary">Szukaj</button>
                <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">Wyczyść</a>
            </div>
            <form method="GET" action="{{ route('recipes.index') }}" class="d-flex align-items-center gap-3 flex-wrap m-0">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="d-flex align-items-center ">
                    <label for="sort" class="form-label mb-0 me-1">Sortuj:</label>
                    <select name="sort" id="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="created_at" {{ $sort == 'created_at' ? 'selected' : '' }}>Data dodania</option>
                        <option value="title" {{ $sort == 'title' ? 'selected' : '' }}>Tytuł</option>
                        <option value="preparation_time" {{ $sort == 'preparation_time' ? 'selected' : '' }}>Czas przygotowania</option>
                        <option value="average_rating" {{ $sort == 'average_rating' ? 'selected' : '' }}>Średnia ocena</option>
                    </select>
                    <label for="order" class="form-label mb-0 ms-3 me-1">Kolejność:</label>
                    <select name="order" id="order" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="asc" {{ $order == 'asc' ? 'selected' : '' }}>Rosnąco</option>
                        <option value="desc" {{ $order == 'desc' ? 'selected' : '' }}>Malejąco</option>
                    </select>
                </div>
            </form>
        </div>
    </form>

    {{-- Lista przepisów --}}
    @if($recipes->isEmpty())
    <p>Brak przepisów do wyświetlenia.</p>
    @else
    <div class="row g-4">
        @foreach ($recipes as $recipe)
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('recipes.show', $recipe->id) }}" class="text-decoration-none text-dark d-block h-100">
                <div class="card h-100 shadow-sm">
                    @if($recipe->image)
                    <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" class="card-img-top" style="object-fit: cover; max-height: 180px;">
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
                        <p class="card-text text-truncate mb-3">
                            <strong>Opis:</strong> {{ $recipe->description }}
                        </p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>


    {{ $recipes->links() }}
    @endif
</div>
@endsection