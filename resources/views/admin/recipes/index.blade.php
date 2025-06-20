@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Przepisy</h1>
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

    {{ $recipes->links() }}
</div>
@endsection
@endadmin