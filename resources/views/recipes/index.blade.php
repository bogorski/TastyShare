@extends('layouts.app')

@section('content')
@foreach($recipes as $recipe)
<div>
    {{ $recipe->title }} <br>
    <a href="/recipes/{{ $recipe->id }}">
        <button>Zobacz przepis</button>
    </a>
</div>
@endforeach
@endsection