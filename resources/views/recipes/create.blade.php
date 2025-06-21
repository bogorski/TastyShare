@extends('layouts.app')

@section('content')
<div class="container my-5 d-flex justify-content-center">
    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" class="shadow-sm p-4 bg-white rounded custom-form">
        @csrf

        <h1>Dodaj nowy przepis</h1>

        <div class="mb-4">
            <label for="title" class="form-label">Tytuł</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="form-label">Opis</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label d-block">Składniki</label>

            @php
            $oldIngredients = old('ingredients', [['ingredient_id' => '', 'quantity' => '', 'unit' => '']]);
            @endphp

            <div id="ingredients-wrapper">
                @foreach ($oldIngredients as $i => $ingredient)
                <div class="ingredient-row d-flex gap-2 align-items-center mb-3">
                    <select name="ingredients[{{ $i }}][ingredient_id]" class="form-select" required>
                        <option value="">-- Wybierz składnik --</option>
                        @foreach ($ingredients as $ing)
                        <option value="{{ $ing->id }}" @selected($ing->id == $ingredient['ingredient_id'])>{{ $ing->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="ingredients[{{ $i }}][quantity]" placeholder="Ilość" class="form-control" value="{{ $ingredient['quantity'] }}" required>
                    <input type="text" name="ingredients[{{ $i }}][unit]" placeholder="Jednostka" class="form-control" value="{{ $ingredient['unit'] }}" required>
                    <button type="button" class="btn btn-danger remove-ingredient" title="Usuń składnik">×</button>
                </div>
                @endforeach
            </div>
            @if ($errors->has('ingredients'))
            <div class="alert alert-danger">{{ $errors->first('ingredients') }}</div>
            @endif
            <button type="button" id="add-ingredient" class="btn btn-success">Dodaj składnik</button>
        </div>

        <div class="mb-4">
            <label for="instructions" class="form-label">Instrukcje</label>
            <textarea name="instructions" id="instructions" class="form-control @error('instructions') is-invalid @enderror">{{ old('instructions') }}</textarea>
            @error('instructions')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="preparation_time" class="form-label">Czas przygotowania (minuty)</label>
            <input type="number" name="preparation_time" id="preparation_time" class="form-control @error('preparation_time') is-invalid @enderror" value="{{ old('preparation_time') }}">
            @error('preparation_time')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="form-label">Zdjęcie (opcjonalne)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-4">
            <label class="form-label d-block">Kategorie</label>
            <div class="@error('categories') is-invalid @enderror">
                @foreach ($categories as $category)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category_{{ $category->id }}" class="form-check-input" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="category_{{ $category->id }}">{{ $category->name }}</label>
                </div>
                @endforeach
                @error('categories')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label d-block">Rodzaje diet</label>
            <div class="@error('diet_types') is-invalid @enderror">
                @foreach ($dietTypes as $dietType)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="diet_types[]" value="{{ $dietType->id }}" id="diet_type_{{ $dietType->id }}" class="form-check-input" {{ in_array($dietType->id, old('diet_types', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="diet_type_{{ $dietType->id }}">{{ $dietType->name }}</label>
                </div>
                @endforeach
                @error('diet_types')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Dodaj przepis</button>
    </form>
</div>

<script>
    let oldIngredients = @json($oldIngredients);
    let ingredientIndex = oldIngredients.length;

    document.getElementById('add-ingredient').addEventListener('click', function() {
        const wrapper = document.getElementById('ingredients-wrapper');
        const newRow = document.createElement('div');
        newRow.classList.add('ingredient-row', 'd-flex', 'gap-2', 'align-items-center', 'mb-3');

        newRow.innerHTML = `
            <select name="ingredients[${ingredientIndex}][ingredient_id]" class="form-select" required>
                <option value="">-- Wybierz składnik --</option>
                @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
            <input type="text" name="ingredients[${ingredientIndex}][quantity]" placeholder="Ilość" class="form-control" required>
            <input type="text" name="ingredients[${ingredientIndex}][unit]" placeholder="Jednostka" class="form-control" required>
            <button type="button" class="btn btn-danger remove-ingredient" title="Usuń składnik">×</button>
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