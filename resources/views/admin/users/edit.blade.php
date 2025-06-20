@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Edytuj użytkownika</h1>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Imię i nazwisko</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adres email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="is_admin" class="form-label">Rola</label>
            <select name="is_admin" id="is_admin" class="form-select" required>
                <option value="0" {{ $user->is_admin ? '' : 'selected' }}>Użytkownik</option>
                <option value="1" {{ $user->is_admin ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Zapisz</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Anuluj</a>
    </form>
</div>
@endsection
@endadmin