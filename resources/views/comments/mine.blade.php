@extends('layouts.app')

@section('title', 'Moje komentarze')

@section('content')
<div class="container my-5">
    <h1 class="fw-bold text-center mb-5">Moje komentarze</h1>

    @if ($comments->isEmpty())
    <div class="alert alert-info">Nie dodałeś jeszcze żadnych komentarzy.</div>
    @else
    <div class="row g-4">
        @foreach ($comments as $comment)
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('recipes.show', $comment->recipe->id) }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $comment->recipe->title }}</h5>
                        <p class="card-text flex-grow-1"><strong>Treść komentarza:</strong> {{ $comment->comment }}</p>
                        <small class="text-muted mt-auto">Dodano: {{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection