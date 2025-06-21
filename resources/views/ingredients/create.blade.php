@extends('layouts.app')

@section('content')
<div class="container my-5 d-flex justify-content-center">
    <form method="POST" action="{{ route('ingredients.store') }}" class="shadow-sm p-4 bg-white rounded custom-form">
        @csrf

        <h1>Dodaj składnik</h1>

        <div class="mb-4">
            <label for="name" class="form-label">Nazwa składnika</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Dodaj</button>
        <a href="{{ route('ingredients.index') }}" class="btn btn-secondary ms-2">Anuluj</a>
    </form>
</div>
@endsection