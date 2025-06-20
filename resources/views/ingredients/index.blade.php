@extends('layouts.app')

@section('content')
<h1>Składniki</h1>

<ul>
    @foreach ($ingredients as $ingredient)
    <li>
        <a href="{{ route('ingredients.show', $ingredient->id) }}">
            {{ $ingredient->name }}
        </a>
    </li>
    @endforeach
</ul>
@endsection