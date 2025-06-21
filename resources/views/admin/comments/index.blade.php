@extends('layouts.app')

@admin
@section('content')
<div class="container my-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <h1 class="fw-bold mb-0">Komentarze</h1>

        <form action="{{ route('admin.comments.index') }}" method="GET" class="d-flex flex-grow-1 flex-md-grow-0">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Wyszukaj po użytkowniku, przepisie lub komentarzu..." style="min-width: 400px;">
            <button type="submit" class="btn btn-primary">Szukaj</button>
        </form>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th>Użytkownik</th>
                    <th>Przepis</th>
                    <th>Komentarz</th>
                    <th style="width: 10%;">Widoczny</th>
                    <th style="width: 15%;">Data dodania</th>
                    <th style="width: 15%;">Data edycji</th>
                    <th style="width: 15%;">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comments as $comment)
                <tr>
                    <td class="fw-semibold">{{ $comment->id }}</td>
                    <td>{{ $comment->user->name ?? 'Anonim' }}</td>
                    <td>{{ $comment->recipe->title ?? 'Brak przepisu' }}</td>
                    <td>{{ Str::limit($comment->comment, 50) }}</td>
                    <td>
                        @if($comment->is_visible)
                        <span class="badge bg-success">Tak</span>
                        @else
                        <span class="badge bg-danger">Nie</span>
                        @endif
                    </td>
                    <td>{{ $comment->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $comment->updated_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn btn-sm btn-primary me-1 mb-1">Edytuj</a>
                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Na pewno chcesz usunąć ten komentarz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger mb-1">Usuń</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted fst-italic py-4">Brak komentarzy do wyświetlenia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $comments->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection
@endadmin