@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dodaj składnik</h1>

    <form method="POST" action="{{ route('ingredients.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nazwa składnika</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Dodaj</button>
        <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">Anuluj</a>
    </form>
</div>
@endsection