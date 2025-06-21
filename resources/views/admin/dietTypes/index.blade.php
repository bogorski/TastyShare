@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Rodzeje diet</h1>
        <a href="{{ route('admin.dietTypes.create') }}" class="btn btn-success">Dodaj dietę</a>
    </div>
    <form action="{{ route('admin.dietTypes.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Wyszukaj nazwę diety...">
            <button class="btn btn-primary" type="submit">Szukaj</button>
        </div>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa diety</th>
                <th>Obraz</th>
                <th>Czy widoczny</th>
                <th>Data dodania</th>
                <th>Data edycji</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dietTypes as $dietType)
            <tr>
                <td>{{ $dietType->id }}</td>
                <td>{{ $dietType->name }}</td>
                <td>
                    @if($dietType->image)
                    <img src="{{ asset('storage/' . $dietType->image) }}" alt="{{ $dietType->name }}" style="max-height: 50px;">
                    @else
                    Brak
                    @endif
                </td>
                <td>{{ $dietType->is_visible ? 'Tak' : 'Nie' }}</td>
                <td>{{ $dietType->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $dietType->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.dietTypes.edit', $dietType->id) }}" class="btn btn-sm btn-primary">Edytuj</a>
                    <form action="{{ route('admin.dietTypes.destroy', $dietType->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Na pewno chcesz usunąć?')">Usuń</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $dietTypes->appends(['search' => request('search')])->links() }}
</div>
@endsection
@endadmin