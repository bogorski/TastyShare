@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Dodaj dietę</h1>

    <form action="{{ route('admin.dietTypes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nazwa</label>
            <input type="text" name="name" id="name" class="form-control" required>
            @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Obraz (opcjonalnie)</label>
            <input type="file" name="image" id="image" class="form-control">
            @error('image')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" name="is_visible" id="is_visible" class="form-check-input" value="1"
                {{ old('is_visible', true) ? 'checked' : '' }}>
            <label for="is_visible" class="form-check-label">Czy widoczny</label>
        </div>


        <button type="submit" class="btn btn-primary">Zapisz</button>
        <a href="{{ route('admin.dietTypes.index') }}" class="btn btn-secondary">Anuluj</a>
    </form>
</div>
@endsection
@endadmin