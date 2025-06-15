@extends('layouts.app')

@section('content')
@foreach($recipes as $recipe)
{{ $recipe->title }} <br>
@endforeach
@endsection