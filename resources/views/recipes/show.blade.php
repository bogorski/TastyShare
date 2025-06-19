@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
<h2>{{ $recipe->title }}</h2>
@if ($isAuthor)
<a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-sm btn-outline-primary mb-3">Edytuj przepis</a>
<form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="mt-3" onsubmit="return confirm('Czy na pewno chcesz usunąć ten przepis?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Usuń przepis</button>
</form>
@endauth

@if($recipe->image)
<img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" style="max-width: 100%; height: auto; margin-bottom: 20px;">
@endif

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
@if ($recipe->comments->count() > 0)
<p><strong>Średnia ocena:</strong>
    {{ number_format($recipe->comments->avg('rating'), 1) }} ★
    ({{ $recipe->comments->count() }} {{ polskaOdmiana('opinia', $recipe->comments->count()) }})
</p>
@endif
<h4 class="mt-5">Komentarze</h4>

@auth
@if($myComment && !$isAuthor)
<a href="#comment-{{ $myComment->id }}"
    onclick="document.getElementById('edit-form-{{ $myComment->id }}').style.display='block'"
    class="btn btn-sm btn-outline-primary mb-3">
    Przejdź do edycji Twojego komentarza
</a>
@endif

@if ($isAuthor)
<p class="mt-3 text-muted">Nie możesz ocenić własnego przepisu.</p>
@elseif(!$userHasCommented && !$isAuthor)
<h5 class="mt-5">Dodaj komentarz</h5>
<form action="{{ route('comments.store', $recipe->id) }}" method="POST" class="mt-3">
    @csrf
    <div class="mb-3">
        <label for="rating" class="form-label">Ocena (1–5):</label>
        <select name="rating" id="rating" class="form-select" required>
            <option value="">Wybierz ocenę</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }} ★</option>
                @endfor
        </select>
    </div>

    <div class="mb-3">
        <label for="content" class="form-label">Komentarz:</label>
        <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
    </div>

    <button type="submit" class="btn btn-success">Dodaj komentarz</button>
</form>
@else
<p class="mt-3 text-muted">Dodałeś już komentarz do tego przepisu.</p>
@endif
@endauth

@guest
<p class="mt-3"> <a href="{{ route('login') }}">Zaloguj się</a>, aby dodać komentarz.</p>
@endguest
@if ($recipe->comments->isEmpty())
<p>Brak komentarzy.</p>
@else
@foreach ($recipe->comments->sortByDesc('created_at') as $comment)
<div class="border rounded p-2 mt-3" id="comment-{{ $comment->id }}">
    <strong>{{ $comment->user->name ?? 'Anonim' }}</strong>
    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>

    @if ($comment->updated_at && $comment->updated_at->gt($comment->created_at))
    <small class="text-muted">(edytowano)</small>
    @endif

    @if ($comment->rating)
    <div class="text-warning">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <=$comment->rating) ★ @else ☆ @endif
            @endfor
    </div>
    @endif

    <p class="mb-0 comment-content">{{ $comment->content }}</p>

    @if (Auth::check() && Auth::id() === $comment->user_id)
    <form action="{{ route('comments.update', $comment->id) }}" method="POST" id="edit-form-{{ $comment->id }}" style="display:none; margin-top: 10px;">
        @csrf
        @method('PUT')

        <select name="rating" required>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ $comment->rating == $i ? 'selected' : '' }}>{{ $i }} ★</option>
                @endfor
        </select>

        <textarea name="content" rows="3" required>{{ $comment->content }}</textarea>

        <button type="submit">Zapisz</button>
        <button type="button" onclick="document.getElementById('edit-form-{{ $comment->id }}').style.display = 'none'; this.closest('div').querySelector('button').style.display='inline-block';">Anuluj</button>
        <button type="button" class="btn btn-danger"
            onclick="if(confirm('Na pewno chcesz usunąć ten komentarz?')) { 
                event.preventDefault();
                document.getElementById('delete-comment-{{ $comment->id }}').submit(); 
            }">
            Usuń
        </button>
    </form>
    <!-- Ukryty formularz do usuwania -->
    <form id="delete-comment-{{ $comment->id }}" action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>
@endforeach

@endif

@endsection