@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Edytuj przepis</h1>
    <form action="{{ route('admin.recipes.update', $recipe->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tytuł</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $recipe->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Opis</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $recipe->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="ingredients" class="form-label">Składniki</label>

            @php
            // jeśli stary input jest, to pobierz go (po błędzie walidacji),
            // jeśli nie — wczytaj składniki z bazy (z pivot)
            $oldIngredients = old('ingredients', $recipeIngredients ?? [['ingredient_id' => '', 'quantity' => '', 'unit' => '']]);
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
            <button type="button" id="add-ingredient" class="btn btn-success mb-3">Dodaj składnik</button>
        </div>

        <div class="mb-3">
            <label class="form-label">Instrukcje</label>
            <textarea name="instructions" class="form-control" rows="4" required>{{ old('instructions', $recipe->instructions) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Czas przygotowania (min)</label>
            <input type="number" name="preparation_time" class="form-control" value="{{ old('preparation_time', $recipe->preparation_time) }}" required>
        </div>

        @if ($recipe->image)
        <div class="mb-3">
            <label class="form-label">Obecne zdjęcie:</label><br>
            <img src="{{ $recipe->image }}" alt="Zdjęcie przepisu" style="max-width: 200px;">
        </div>
        @endif

        <div class="mb-3">
            <label for="image" class="form-label">Zmień zdjęcie</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

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

        <div class="form-check mb-3">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" class="form-check-input" value="1" {{ old('is_visible', $recipe->is_visible) ? 'checked' : '' }}>
            <label for="is_visible" class="form-check-label">Czy widoczny</label>
        </div>

        <button type="submit" class="btn btn-success">Zapisz zmiany</button>
        <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">Anuluj</a>
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
@endadmin