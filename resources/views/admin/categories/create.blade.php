@extends('layouts.app')

@admin
@section('content')
<div class="container my-5 d-flex justify-content-center">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="shadow-sm p-4 bg-white rounded custom-form">
        @csrf

        <h1>Dodaj kategorię</h1>

        <div class="mb-4">
            <label for="name" class="form-label">Nazwa</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="form-label">Obraz (opcjonalnie)</label>
            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" class="form-check-input" value="1" {{ old('is_visible', true) ? 'checked' : '' }}>
            <label for="is_visible" class="form-check-label">Czy widoczny</label>
        </div>

        <button type="submit" class="btn btn-primary">Zapisz</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary ms-2">Anuluj</a>
    </form>
</div>
@endsection
@endadmin