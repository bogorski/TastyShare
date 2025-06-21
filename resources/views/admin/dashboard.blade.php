@extends('layouts.app')

@admin
@section('content')
<div class="container">
    <h1>Panel administracyjny</h1>
    <p>Witamy w panelu admina!</p>

    <ul>
        <li><a href="{{ route('admin.users.index') }}">Użytkownicy</a></li>
        <li><a href="{{ route('admin.recipes.index') }}">Przepisy</a></li>
        <li><a href="{{ route('admin.comments.index') }}">Komentarze</a></li>
        <li><a href="{{ route('admin.categories.index') }}">Kategorie</a></li>
        <li><a href="{{ route('admin.dietTypes.index') }}">Rodzaje diet</a></li>
        <li><a href="{{ route('admin.ingredients.index') }}">Składniki</a></li>
        <li><a href="{{ route('home') }}">Powrót do strony głównej</a></li>
    </ul>
</div>
@endsection
@endadmin