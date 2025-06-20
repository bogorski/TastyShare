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
                        {{ $ing->nazwa }}
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
                    <option value="{{ $ingredient->id }}">{{ $ingredient->nazwa }}</option>
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