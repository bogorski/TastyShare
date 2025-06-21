@extends('layouts.app')

@admin
@section('content')
<div class="container my-5">
    <h1 class="mb-4 fw-bold text-center">Edytuj przepis</h1>

    <form action="{{ route('admin.recipes.update', $recipe->id) }}" method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width: 720px;">
        @csrf
        @method('PUT')

        {{-- Tytuł --}}
        <div class="mb-4">
            <label for="title" class="form-label fw-semibold">Tytuł</label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $recipe->title) }}"
                required>
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Opis --}}
        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">Opis</label>
            <textarea
                id="description"
                name="description"
                rows="3"
                class="form-control @error('description') is-invalid @enderror"
                required>{{ old('description', $recipe->description) }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Składniki --}}
        <div class="mb-4">
            <label for="ingredients-wrapper" class="form-label fw-semibold">Składniki</label>

            @php
            $oldIngredients = old('ingredients', $recipeIngredients ?? [['ingredient_id' => '', 'quantity' => '', 'unit' => '']]);
            @endphp

            <div id="ingredients-wrapper" class="mb-3">
                @foreach ($oldIngredients as $i => $ingredient)
                <div class="ingredient-row d-flex gap-2 align-items-center mb-2">
                    <select
                        name="ingredients[{{ $i }}][ingredient_id]"
                        class="form-select flex-grow-1 @error('ingredients.' . $i . '.ingredient_id') is-invalid @enderror"
                        required>
                        <option value="">-- Wybierz składnik --</option>
                        @foreach ($ingredients as $ing)
                        <option value="{{ $ing->id }}" @selected($ing->id == $ingredient['ingredient_id'])>
                            {{ $ing->name }}
                        </option>
                        @endforeach
                    </select>
                    <input
                        type="text"
                        name="ingredients[{{ $i }}][quantity]"
                        placeholder="Ilość"
                        class="form-control @error('ingredients.' . $i . '.quantity') is-invalid @enderror"
                        style="max-width: 100px;"
                        value="{{ $ingredient['quantity'] }}"
                        required>
                    <input
                        type="text"
                        name="ingredients[{{ $i }}][unit]"
                        placeholder="Jednostka"
                        class="form-control @error('ingredients.' . $i . '.unit') is-invalid @enderror"
                        style="max-width: 100px;"
                        value="{{ $ingredient['unit'] }}"
                        required>
                    <button type="button" class="btn btn-danger remove-ingredient" title="Usuń składnik">&times;</button>
                </div>
                @endforeach
            </div>

            @if ($errors->has('ingredients'))
            <div class="alert alert-danger">{{ $errors->first('ingredients') }}</div>
            @endif

            <button type="button" id="add-ingredient" class="btn btn-success">Dodaj składnik</button>
        </div>

        {{-- Instrukcje --}}
        <div class="mb-4">
            <label for="instructions" class="form-label fw-semibold">Instrukcje</label>
            <textarea
                id="instructions"
                name="instructions"
                rows="5"
                class="form-control @error('instructions') is-invalid @enderror"
                required>{{ old('instructions', $recipe->instructions) }}</textarea>
            @error('instructions')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Czas przygotowania --}}
        <div class="mb-4">
            <label for="preparation_time" class="form-label fw-semibold">Czas przygotowania (minuty)</label>
            <input
                type="number"
                id="preparation_time"
                name="preparation_time"
                class="form-control @error('preparation_time') is-invalid @enderror"
                value="{{ old('preparation_time', $recipe->preparation_time) }}"
                min="1"
                required>
            @error('preparation_time')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Obecne zdjęcie --}}
        @if ($recipe->image)
        <div class="mb-4 text-center">
            <label class="form-label fw-semibold">Obecne zdjęcie:</label><br>
            <img src="{{ $recipe->image }}" alt="Zdjęcie przepisu" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
        </div>
        @endif

        {{-- Zmień zdjęcie --}}
        <div class="mb-4">
            <label for="image" class="form-label fw-semibold">Zmień zdjęcie</label>
            <input
                type="file"
                id="image"
                name="image"
                class="form-control @error('image') is-invalid @enderror"
                accept="image/*">
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kategorie --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Kategorie</label>
            <div class="d-flex flex-wrap gap-3">
                @foreach($categories as $category)
                <div class="form-check">
                    <input
                        type="checkbox"
                        name="categories[]"
                        value="{{ $category->id }}"
                        class="form-check-input"
                        id="category-{{ $category->id }}"
                        {{ in_array($category->id, old('categories', $recipe->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->name }}</label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Rodzaje diet --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Rodzaje diet</label>
            <div class="d-flex flex-wrap gap-3">
                @foreach($dietTypes as $dietType)
                <div class="form-check">
                    <input
                        type="checkbox"
                        name="diet_types[]"
                        value="{{ $dietType->id }}"
                        class="form-check-input"
                        id="diettype-{{ $dietType->id }}"
                        {{ in_array($dietType->id, old('diet_types', $recipe->dietTypes->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <label class="form-check-label" for="diettype-{{ $dietType->id }}">{{ $dietType->name }}</label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Widoczność --}}
        <div class="form-check mb-4">
            <input type="hidden" name="is_visible" value="0">
            <input
                type="checkbox"
                name="is_visible"
                id="is_visible"
                class="form-check-input"
                value="1"
                {{ old('is_visible', $recipe->is_visible) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_visible">Czy widoczny</label>
        </div>

        {{-- Przyciski --}}
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary flex-grow-1">Zapisz zmiany</button>
            <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary flex-grow-1">Anuluj</a>
        </div>
    </form>
</div>

<script>
    let oldIngredients = @json($oldIngredients);
    let ingredientIndex = oldIngredients.length;

    document.getElementById('add-ingredient').addEventListener('click', function() {
        const wrapper = document.getElementById('ingredients-wrapper');
        const newRow = document.createElement('div');
        newRow.classList.add('ingredient-row', 'd-flex', 'gap-2', 'align-items-center', 'mb-2');

        newRow.innerHTML = `
            <select name="ingredients[\${ingredientIndex}][ingredient_id]" class="form-select flex-grow-1" required>
                <option value="">-- Wybierz składnik --</option>
                @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
            <input type="text" name="ingredients[\${ingredientIndex}][quantity]" placeholder="Ilość" class="form-control" style="max-width: 100px;" required>
            <input type="text" name="ingredients[\${ingredientIndex}][unit]" placeholder="Jednostka" class="form-control" style="max-width: 100px;" required>
            <button type="button" class="btn btn-danger remove-ingredient" title="Usuń składnik">&times;</button>
        `;

        wrapper.appendChild(newRow);
        ingredientIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ingredient')) {
            e.target.closest('.ingredient-row').remove();
        }
    });
</script>
@endsection
@endadmin