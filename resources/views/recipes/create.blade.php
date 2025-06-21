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

            @php
            // Pobieramy poprzednio wprowadzone składniki z funkcji old().
            // Jeśli to pierwszy raz, ustawiamy domyślną tablicę z jednym "pustym" składnikiem.
            $oldIngredients = old('ingredients', [['ingredient_id' => '', 'quantity' => '', 'unit' => '']]);
            @endphp
            <div id="ingredients-wrapper">
                @foreach ($oldIngredients as $i => $ingredient)
                <div class="ingredient-row d-flex gap-2 align-items-center mb-2">

                    <!-- Pole wyboru składnika -->
                    <select name="ingredients[{{ $i }}][ingredient_id]" class="form-select" required>
                        <option value="">-- Wybierz składnik --</option>
                        @foreach ($ingredients as $ing)
                        <!-- Opcja jest zaznaczona jeśli id składnika zgadza się z tym z old() -->
                        <option value="{{ $ing->id }}" @selected($ing->id == $ingredient['ingredient_id'])>
                            {{ $ing->name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Pole na ilość składnika -->
                    <input type="text" name="ingredients[{{ $i }}][quantity]" placeholder="Ilość" class="form-control" value="{{ $ingredient['quantity'] }}" required>

                    <!-- Pole na jednostkę -->
                    <input type="text" name="ingredients[{{ $i }}][unit]" placeholder="Jednostka" class="form-control" value="{{ $ingredient['unit'] }}" required>

                    <!-- Przycisk usuwania danego wiersza składnika (np. z JS) -->
                    <button type="button" class="btn btn-danger remove-ingredient">Usuń</button>
                </div>

                @endforeach
                @if ($errors->has('ingredients'))
                <div class="alert alert-danger">
                    {{ $errors->first('ingredients') }}
                </div>
                @endif
            </div>
        </div>

        <button type="button" id="add-ingredient" class="btn btn-success mb-3">Dodaj składnik</button>
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

<script>
    // Pobierz aktualną liczbę wierszy
    //zamienia zmienną PHP $oldIngredients na poprawny format JSON
    let oldIngredients = @json($oldIngredients);
    let ingredientIndex = oldIngredients.length;

    document.getElementById('add-ingredient').addEventListener('click', function() {
        const wrapper = document.getElementById('ingredients-wrapper');
        const newRow = document.createElement('div');
        newRow.classList.add('ingredient-row', 'd-flex', 'gap-2', 'align-items-center', 'mb-2');

        // Ustawiamy pola z nowym indeksem
        newRow.innerHTML = `
            <select name="ingredients[${ingredientIndex}][ingredient_id]" class="form-select" required>
                <option value="">-- Wybierz składnik --</option>
                @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
            <input type="text" name="ingredients[${ingredientIndex}][quantity]" placeholder="Ilość" class="form-control" required>
            <input type="text" name="ingredients[${ingredientIndex}][unit]" placeholder="Jednostka" class="form-control" required>
            <button type="button" class="btn btn-danger remove-ingredient">Usuń</button>
        `;

        wrapper.appendChild(newRow);
        ingredientIndex++;
    });

    // Usuwanie wiersza po kliknięciu "Usuń"
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ingredient')) {
            e.target.closest('.ingredient-row').remove();
        }
    });
</script>
@endsection