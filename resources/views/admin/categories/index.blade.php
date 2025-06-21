@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Kategorie</h1>
        <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Szukaj...">
            <button type="submit" class="btn btn-outline-primary">Szukaj</button>
        </form>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success ms-2">Dodaj kategorię</a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa kategorii</th>
                <th>Obraz</th>
                <th>Czy widoczny</th>
                <th>Data dodania</th>
                <th>Data edycji</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="max-height: 50px;">
                    @else
                    Brak
                    @endif
                </td>
                <td>{{ $category->is_visible ? 'Tak' : 'Nie' }}</td>
                <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $category->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edytuj</a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Na pewno chcesz usunąć kategorię?')">Usuń</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $categories->appends(['search' => request('search')])->links() }}
</div>
@endsection
@endadmin