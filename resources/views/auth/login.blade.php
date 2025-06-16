@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 400px;">
    <h2>Logowanie</h2>
    <form method="POST" action="{{ route('login.perform') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Hasło</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Zapamiętaj mnie</label>
        </div>

        <button type="submit" class="btn btn-primary">Zaloguj się</button>
    </form>
</div>
@endsection