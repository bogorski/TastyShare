@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="border rounded bg-white p-4 w-25 mx-auto shadow-sm">
        <h2 class="mb-4 text-center">Resetuj hasło</h2>

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Adres email</label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">Wyślij link resetujący</button>
        </form>
    </div>
</div>
@endsection