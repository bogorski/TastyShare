@extends('layouts.app')

@section('title', 'Edytuj przepis: ' . $recipe->title)

@section('content')
<h2>Edytuj przepis</h2>

<form action="{{ route('recipes.update', $recipe->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Tytuł --}}
    <div class="mb-3">
        <label for="title" class="form-label">Tytuł</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $recipe->title) }}" required>
    </div>

    {{-- Opis --}}
    <div class="mb-3">
        <label for="description" class="form-label">Opis</label>
        <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $recipe->description) }}</textarea>
    </div>

    {{-- Składniki --}}
    <div class="mb-3">
        <label for="ingredients" class="form-label">Składniki</label>
        <textarea name="ingredients" id="ingredients" class="form-control" rows="3" required>{{ old('ingredients', $recipe->ingredients) }}</textarea>
    </div>

    {{-- Instrukcje --}}
    <div class="mb-3">
        <label for="instructions" class="form-label">Instrukcje</label>
        <textarea name="instructions" id="instructions" class="form-control" rows="5" required>{{ old('instructions', $recipe->instructions) }}</textarea>
    </div>

    {{-- Czas przygotowania --}}
    <div class="mb-3">
        <label for="preparation_time" class="form-label">Czas przygotowania (minuty)</label>
        <input type="number" name="preparation_time" id="preparation_time" class="form-control" value="{{ old('preparation_time', $recipe->preparation_time) }}" required min="1">
    </div>

    {{-- Obecne zdjęcie --}}
    @if ($recipe->image)
    <div class="mb-3">
        <label class="form-label">Obecne zdjęcie:</label><br>
        <img src="{{ $recipe->image }}" alt="Zdjęcie przepisu" style="max-width: 200px;">
    </div>
    @endif

    {{-- Nowe zdjęcie --}}
    <div class="mb-3">
        <label for="image" class="form-label">Zmień zdjęcie</label>
        <input type="file" name="image" id="image" class="form-control">
    </div>

    {{-- Kategorie --}}
    <div class="mb-3">
        <label class="form-label">Kategorie</label>
        <div class="form-check">
            @foreach($categories as $category)
            <div class="form-check">
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                    class="form-check-input" id="category-{{ $category->id }}"
                    {{ in_array($category->id, $recipe->categories->pluck('id')->toArray()) ? 'checked' : '' }}>
                <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->name }}</label>
            </div>
            @endforeach
        </div>
    </div>


    {{-- Typy diety --}}
    <div class="mb-3">
        <label class="form-label">Rodzaje diet</label>
        <div class="form-check">
            @foreach($dietTypes as $dietType)
            <div class="form-check">
                <input type="checkbox" name="diet_types[]" value="{{ $dietType->id }}"
                    class="form-check-input" id="diettype-{{ $dietType->id }}"
                    {{ in_array($dietType->id, $recipe->dietTypes->pluck('id')->toArray()) ? 'checked' : '' }}>
                <label class="form-check-label" for="diettype-{{ $dietType->id }}">{{ $dietType->name }}</label>
            </div>
            @endforeach
        </div>
    </div>


    {{-- Przyciski --}}
    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
    <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-secondary">Anuluj</a>
</form>
@endsection