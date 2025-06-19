@extends('layouts.app')

@section('title', 'Zapomniałeś hasła?')

@section('content')
<div class="container mx-auto max-w-md mt-10">
    <h2 class="text-2xl font-bold mb-4">Resetuj hasło</h2>

    @if (session('status'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium">Adres e-mail</label>
            <input type="email" name="email" id="email" required autofocus class="w-full px-3 py-2 border rounded" value="{{ old('email') }}">
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Wyślij link resetujący
        </button>
    </form>
</div>
@endsection