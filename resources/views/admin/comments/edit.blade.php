@extends('layouts.app')

@admin
@section('content')
<div class="container my-5 d-flex justify-content-center">
    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" class="shadow-sm p-4 bg-white rounded custom-form">
        @csrf
        @method('PUT')

        <h1 class="mb-4">Edytuj komentarz</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <label for="comment" class="form-label">Treść komentarza</label>
            <textarea
                name="comment"
                id="comment"
                class="form-control @error('comment') is-invalid @enderror"
                rows="5"
                required>{{ old('comment', $comment->comment) }}</textarea>
            @error('comment')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input type="hidden" name="is_visible" value="0">
            <input
                type="checkbox"
                name="is_visible"
                id="is_visible"
                class="form-check-input"
                value="1"
                {{ old('is_visible', $comment->is_visible) ? 'checked' : '' }}>
            <label for="is_visible" class="form-check-label">Czy widoczny</label>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Anuluj</a>
        </div>
    </form>
</div>
@endsection
@endadmin