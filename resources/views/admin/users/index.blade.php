@extends('layouts.app')

@admin
@section('content')
<div class="container">

    <h1 class="fw-bold">Użytkownicy</h1>
    <form action="{{ route('admin.users.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Szukaj użytkownika..."
                value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Szukaj</button>
        </div>
    </form>
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
    {{ $users->links() }}
</div>
@endsection
@endadmin