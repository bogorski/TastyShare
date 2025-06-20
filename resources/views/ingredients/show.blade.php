@extends('layouts.app')

@section('content')
<h1>Przepisy ze składnikiem: {{ $ingredient->name }}</h1>

@if($recipes->isEmpty())
<p>Brak przepisów z tym składnikiem.</p>
@else
@foreach ($recipes as $recipe)
<div>
    <h3><a href="{{ route('recipes.show', $recipe->id) }}">{{ $recipe->title }}</a></h3>
    <p>Składniki: {{ $recipe->ingredients->pluck('name')->join(', ') }}</p>
</div>
@endforeach

{{ $recipes->links() }}
@endif
@endsection