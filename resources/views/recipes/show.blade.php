@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
<h2>{{ $recipe->title }}</h2>

<p><strong>Opis:</strong> {{ $recipe->description }}</p>
<p><strong>Składniki:</strong> {{ $recipe->ingredients }}</p>
<p><strong>Instrukcje:</strong> {{ $recipe->instructions }}</p>
<p><strong>Czas przygotowania:</strong> {{ $recipe->preparation_time }} minut</p>

<p><strong>Kategoria:</strong>
    @foreach ($recipe->categories as $category)
    {{ $category->name }}@if (!$loop->last), @endif
    @endforeach
</p>
<p><strong>Dieta:</strong>
    @foreach ($recipe->dietTypes as $dietType)
    {{ $dietType->name }}@if (!$loop->last), @endif
    @endforeach
</p>
<p><strong>Dodany przez:</strong> {{ $recipe->user->name }}</p>
@endsection