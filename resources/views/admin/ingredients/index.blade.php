@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold">Składniki</h1>
        <a href="{{ route('admin.ingredients.create') }}" class="btn btn-success">Dodaj składnik</a>
    </div>
    <form action="{{ route('admin.ingredients.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ old('search', $search) }}" class="form-control"
                placeholder="Wyszukaj składnik...">
            <button type="submit" class="btn btn-primary">Szukaj</button>
        </div>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa składnika</th>
                <th>Widoczny</th>
                <th>Data dodania</th>
                <th>Data edycji</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ingredients as $ingredient)
            <tr>
                <td>{{ $ingredient->id }}</td>
                <td>{{ $ingredient->name }}</td>
                <td>{{ $ingredient->is_visible ? 'Tak' : 'Nie' }}</td>
                <td>{{ $ingredient->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $ingredient->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.ingredients.edit', $ingredient->id) }}" class="btn btn-sm btn-primary">Edytuj</a>
                    <form action="{{ route('admin.ingredients.destroy', $ingredient->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Na pewno chcesz usunąć ten składnik?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Usuń</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@endadmin