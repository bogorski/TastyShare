@extends('layouts.app')

@section('title', 'Moje komentarze')

@section('content')
<div class="container">
    <h1>Moje komentarze</h1>

    @if ($comments->isEmpty())
    <p>Nie dodałeś jeszcze żadnych komentarzy.</p>
    @else
    <div class="list-group">
        @foreach ($comments as $comment)
        <div class="list-group-item mb-3">
            <h5><a href="{{ route('recipes.show', $comment->recipe->id) }}">{{ $comment->recipe->title }}</a></h5>
            <p><strong>Treść:</strong> {{ $comment->comment }}</p>
            <p>{{ $comment->content }}</p>
            <small class="text-muted">Dodano: {{ $comment->created_at->diffForHumans() }}</small>
        </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection