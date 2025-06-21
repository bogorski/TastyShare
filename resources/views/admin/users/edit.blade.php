@extends('layouts.app')

@admin
@section('content')
<div class="container my-5">
    <div class="mx-auto" style="max-width: 600px;">
        <h1 class="mb-4">Edytuj użytkownika</h1>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Imię i nazwisko</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $user->name) }}"
                    class="form-control @error('name') is-invalid @enderror"
                    required
                    autofocus>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adres email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', $user->email) }}"
                    class="form-control @error('email') is-invalid @enderror"
                    required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="is_admin" class="form-label">Rola</label>
                <select
                    name="is_admin"
                    id="is_admin"
                    class="form-select @error('is_admin') is-invalid @enderror"
                    required>
                    <option value="0" {{ old('is_admin', $user->is_admin) == 0 ? 'selected' : '' }}>Użytkownik</option>
                    <option value="1" {{ old('is_admin', $user->is_admin) == 1 ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('is_admin')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Zapisz</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary flex-grow-1 text-center">Anuluj</a>
            </div>
        </form>
    </div>
</div>
@endsection
@endadmin