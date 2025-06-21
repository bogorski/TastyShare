@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold">Przepisy</h1>
        <form action="{{ route('admin.recipes.index') }}" method="GET" class="d-flex gap-2 align-items-center mb-3" style="flex-wrap: wrap;">
            <select name="filter" class="form-select w-auto">
                <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Wszystko</option>
                <option value="title" {{ request('filter') == 'title' ? 'selected' : '' }}>Tytuł</option>
                <option value="description" {{ request('filter') == 'description' ? 'selected' : '' }}>Opis</option>
                <option value="instructions" {{ request('filter') == 'instructions' ? 'selected' : '' }}>Instrukcje</option>
                <option value="author" {{ request('filter') == 'author' ? 'selected' : '' }}>Autor</option>
                <option value="ingredient" {{ request('filter') == 'ingredient' ? 'selected' : '' }}>Składnik</option>
            </select>

            <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto" placeholder="Wpisz frazę...">

            <button type="submit" class="btn btn-outline-primary">Szukaj</button>
        </form>
    </div>
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Instrukcje</th>
                <th>Czas (min)</th>
                <th>Obraz</th>
                <th>Autor</th>
                <th>Status</th>
                <th>Czy widoczny</th>
                <th>Składniki</th>
                <th>Data dodania</th>
                <th>Data edycji</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recipes as $recipe)
            <tr>
                <td>{{ $recipe->id }}</td>
                <td>{{ $recipe->title }}</td>
                <td>{{ Str::limit($recipe->description, 30) }}</td>
                <td>{{ Str::limit($recipe->instructions, 30) }}</td>
                <td>{{ $recipe->preparation_time }}</td>
                <td>
                    @if ($recipe->image)
                    <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" style="max-width:100px;">
                    @endif
                </td>
                <td>{{ $recipe->user->name ?? 'Anonim' }}</td>
                <td>{{ $recipe->status ?? '-' }}</td>
                <td>{{ $recipe->is_visible ? 'Tak' : 'Nie' }}</td>
                <td>
                    @if($recipe->ingredients->count())
                    {{ $recipe->ingredients->pluck('name')->join(', ') }}
                    @else
                    Brak
                    @endif
                </td>
                <td>{{ $recipe->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $recipe->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <!-- Edycja -->
                    <a href="{{ route('admin.recipes.edit', $recipe->id) }}" class="btn btn-sm btn-primary">
                        Edytuj
                    </a>

                    <!-- Usuwanie -->
                    <form action="{{ route('admin.recipes.destroy', $recipe->id) }}" method="POST" style="display:inline;"
                        onsubmit="return confirm('Na pewno chcesz usunąć ten przepis?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Usuń</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $recipes->appends(['search' => request('search')])->links() }}
</div>
@endsection
@endadmin