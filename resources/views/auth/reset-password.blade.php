@extends('layouts.app')

@section('title', 'Ustaw nowe hasło')

@section('content')
<div class="container my-5">
    <div class="border rounded bg-white p-4 w-25 mx-auto shadow-sm">
        <h2 class="mb-4 text-center">Ustaw nowe hasło</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">Adres email</label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    required
                    autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nowe hasło</label>
                <input
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    required>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Potwierdź hasło</label>
                <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    name="password_confirmation"
                    required>
            </div>

            <button type="submit" class="btn btn-success w-100">Zresetuj hasło</button>
        </form>
    </div>
</div>
@endsection