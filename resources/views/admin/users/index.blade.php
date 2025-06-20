@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Użytkownicy</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię i nazwisko</th>
                <th>Email</th>
                <th>Rola</th>
                <th>Data rejestracji</th>
                <th>Data edycji</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->is_admin ? 'Administrator' : 'Użytkownik' }}</td>
                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edytuj</a>

                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;"
                        onsubmit="return confirm('Na pewno chcesz usunąć tego użytkownika?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Usuń</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- paginacja -->
    {{ $users->links() }}
</div>
@endsection
@endadmin