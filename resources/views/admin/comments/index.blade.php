@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Komentarze</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Użytkownik</th>
                <th>Przepis</th>
                <th>Komentarz</th>
                <th>Czy widoczny?</th>
                <th>Data dodania</th>
                <th>Data edycji</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
            <tr>
                <td>{{ $comment->id }}</td>
                <td>{{ $comment->user->name ?? 'Anonim' }}</td>
                <td>{{ $comment->recipe->title ?? 'Brak przepisu' }}</td>
                <td>{{ Str::limit($comment->comment, 50) }}</td>
                <td>{{ $comment->is_visible ? 'Tak' : 'Nie' }}</td>
                <td>{{ $comment->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $comment->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <!-- Edycja -->
                    <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn btn-sm btn-primary">
                        Edytuj
                    </a>

                    <!-- Usuwanie -->
                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" style="display:inline;"
                        onsubmit="return confirm('Na pewno chcesz usunąć ten komentarz?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Usuń</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $comments->links() }}
</div>
@endsection
@endadmin