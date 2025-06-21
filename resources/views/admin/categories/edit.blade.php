@extends('layouts.app')

@admin
@section('content')
<div class="container my-5 d-flex justify-content-center">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data"
        class="shadow-sm p-4 bg-white rounded custom-form">
        @csrf
        @method('PUT')

        <h1 class="mb-4">Edytuj kategorię</h1>

        <div class="mb-4">
            <label for="name" class="form-label">Nazwa kategorii</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $category->name) }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="form-label">Obraz kategorii</label>
            @if($category->image)
            <div class="mb-3">
                <img src="{{ asset('storage/' . $category->image) }}" alt="Obraz kategorii" class="img-fluid w-50">
            </div>
            @endif
            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" class="form-check-input" value="1"
                {{ old('is_visible', $category->is_visible) ? 'checked' : '' }}>
            <label for="is_visible" class="form-check-label">Czy widoczny</label>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">Zapisz zmiany</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Anuluj</a>
        </div>
    </form>
</div>
@endsection
@endadmin