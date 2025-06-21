@extends('layouts.app')

@admin
@section('content')
<div class="container my-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <h1 class="mb-0 fw-bold">Kategorie</h1>

        <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex flex-grow-1 flex-md-grow-0 gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Szukaj kategorii...">
            <button type="submit" class="btn btn-primary">Szukaj</button>
        </form>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">Dodaj kategorię</a>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th>Nazwa kategorii</th>
                    <th style="width: 10%;">Obraz</th>
                    <th style="width: 10%;">Widoczna</th>
                    <th style="width: 15%;">Data dodania</th>
                    <th style="width: 15%;">Data edycji</th>
                    <th style="width: 15%;">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                <tr>
                    <td class="fw-semibold">{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded">
                        @else
                        <span class="text-muted fst-italic">Brak</span>
                        @endif
                    </td>
                    <td>
                        @if($category->is_visible)
                        <span class="badge bg-success">Tak</span>
                        @else
                        <span class="badge bg-secondary">Nie</span>
                        @endif
                    </td>
                    <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $category->updated_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary me-1 mb-1">Edytuj</a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Na pewno chcesz usunąć kategorię?')">
                                Usuń
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted fst-italic py-4">Brak kategorii do wyświetlenia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $categories->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection
@endadmin