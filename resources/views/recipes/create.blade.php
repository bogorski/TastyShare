@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dodaj nowy przepis</h1>

    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Tytuł</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Opis</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ingredients" class="form-label">Składniki</label>
            <textarea name="ingredients" id="ingredients" class="form-control @error('ingredients') is-invalid @enderror">{{ old('ingredients') }}</textarea>
            @error('ingredients')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="instructions" class="form-label">Instrukcje</label>
            <textarea name="instructions" id="instructions" class="form-control @error('instructions') is-invalid @enderror">{{ old('instructions') }}</textarea>
            @error('instructions')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="preparation_time" class="form-label">Czas przygotowania (minuty)</label>
            <input type="number" name="preparation_time" id="preparation_time" class="form-control @error('preparation_time') is-invalid @enderror" value="{{ old('preparation_time') }}">
            @error('preparation_time')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Zdjęcie (opcjonalne)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Kategorie</label>
            <div class="@error('categories') is-invalid @enderror">
                @foreach ($categories as $category)
                <div class="form-check">
                    <input
                        type="checkbox"
                        name="categories[]"
                        value="{{ $category->id }}"
                        id="category_{{ $category->id }}"
                        class="form-check-input"
                        {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="category_{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                </div>
                @endforeach
                @error('categories')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Rodzaje diet</label>
            <div class="@error('diet_types') is-invalid @enderror">
                @foreach ($dietTypes as $dietType)
                <div class="form-check">
                    <input
                        type="checkbox"
                        name="diet_types[]"
                        value="{{ $dietType->id }}"
                        id="diet_type_{{ $dietType->id }}"
                        class="form-check-input"
                        {{ in_array($dietType->id, old('diet_types', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="diet_type_{{ $dietType->id }}">
                        {{ $dietType->name }}
                    </label>
                </div>
                @endforeach
                @error('diet_types')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Dodaj przepis</button>
    </form>
</div>
@endsection