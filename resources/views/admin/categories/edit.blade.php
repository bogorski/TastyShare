@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Edytuj kategorię</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nazwa kategorii</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $category->name) }}" required>
            @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Obraz kategorii</label><br>
            @if($category->image)
            <img src="{{ asset('storage/' . $category->image) }}" alt="Obraz kategorii" style="max-height: 100px; margin-bottom: 10px;">
            @endif
            <input type="file" name="image" id="image" class="form-control">
            @error('image')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" class="form-check-input" value="1"
                {{ old('is_visible', $category->is_visible) ? 'checked' : '' }}>
            <label for="is_visible" class="form-check-label">Czy widoczny</label>
        </div>

        <button type="submit" class="btn btn-success">Zapisz zmiany</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Anuluj</a>
    </form>
</div>
@endsection
@endadmin