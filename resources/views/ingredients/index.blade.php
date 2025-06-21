@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Składniki</h1>

        @auth
        <a href="{{ route('ingredients.create') }}" class="btn btn-success">Dodaj składnik</a>
        @endauth
    </div>

    <form action="{{ route('ingredients.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ old('search', $search) }}" class="form-control"
                placeholder="Wyszukaj składnik...">
            <button type="submit" class="btn btn-primary">Szukaj</button>
        </div>
    </form>

    @if ($ingredients->isEmpty())
    <div class="alert alert-info">Brak dostępnych składników.</div>
    @else
    <ul class="list-group">
        @foreach ($ingredients as $ingredient)
        <li class="list-group-item">
            <a href="{{ route('ingredients.show', $ingredient->id) }}">
                {{ $ingredient->name }}
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection