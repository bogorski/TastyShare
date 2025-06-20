@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Składniki</h1>
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
    {{ $ingredients->links() }}
</div>
@endsection
@endadmin