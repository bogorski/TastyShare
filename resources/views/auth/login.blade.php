@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="border rounded bg-white p-4 w-25 mx-auto shadow-sm">
        <h2 class="mb-4 text-center">Logowanie</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email"
                    value="{{ old('email') }}"
                    required autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Hasło</label>
                <input type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password" name="password"
                    required>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Zapamiętaj mnie</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Zaloguj się</button>
            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none">
                    Zapomniałeś hasła?
                </a>
            </div>
        </form>
    </div>
</div>


@endsection