@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Edytuj dietę</h1>

    <form action="{{ route('admin.dietTypes.update', $dietType->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nazwa diety</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $dietType->name) }}" required>
            @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Obraz</label><br>
            @if($dietType->image)
            <img src="{{ asset('storage/' . $dietType->image) }}" alt="Obraz" style="max-height: 100px; margin-bottom: 10px;">
            @endif
            <input type="file" name="image" id="image" class="form-control">
            @error('image')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" class="form-check-input" value="1"
                {{ old('is_visible', $dietType->is_visible) ? 'checked' : '' }}>
            <label for="is_visible" class="form-check-label">Czy widoczny</label>
        </div>

        <button type="submit" class="btn btn-success">Zapisz zmiany</button>
        <a href="{{ route('admin.dietTypes.index') }}" class="btn btn-secondary">Anuluj</a>
    </form>
</div>
@endsection
@endadmin