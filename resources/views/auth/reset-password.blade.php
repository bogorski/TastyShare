@extends('layouts.app')

@section('title', 'Ustaw nowe hasło')

@section('content')
<div class="container mx-auto max-w-md mt-10">
    <h2 class="text-2xl font-bold mb-4">Ustaw nowe hasło</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium">Adres e-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email', $email) }}" required class="w-full px-3 py-2 border rounded">
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium">Nowe hasło</label>
            <input type="password" name="password" id="password" required class="w-full px-3 py-2 border rounded">
            @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium">Potwierdź hasło</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-3 py-2 border rounded">
        </div>

        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Zresetuj hasło
        </button>
    </form>
</div>
@endsection