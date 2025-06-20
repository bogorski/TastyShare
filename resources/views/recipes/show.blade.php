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
<p><strong>Składniki:</strong></p>
<ul>
    @foreach($recipe->ingredients as $ingredient)
    <li>
        {{ $ingredient->name }}:
        {{ $ingredient->pivot->quantity }}
        {{ $ingredient->pivot->unit }}
    </li>
    @endforeach
</ul>
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
<p><strong>Średnia ocena:</strong>
    {{ number_format($recipe->ratings->avg('rating'), 1) }} ★
    ({{ $recipe->comments->count() }} {{ polskaOdmiana('opinia', $recipe->comments->count()) }})
</p>
@auth
@if ($isAuthor)
<p class="mt-3 text-muted">Nie możesz ocenić własnego przepisu.</p>
@elseif(!$userHasRated && !$isAuthor)
<h5 class="mt-5">Dodaj ocene</h5>
<form action="{{ route('ratings.store', $recipe->id) }}" method="POST" class="mt-3">
    @csrf
    <div class="mb-3">
        <label for="rating" class="form-label">Ocena (1-5):</label>
        <select name="rating" id="rating" class="form-select" required>
            <option value="">Wybierz ocenę</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }} ★</option>
                @endfor
        </select>
    </div>
    <button type="submit" class="btn btn-success">Dodaj ocene</button>
</form>
@else
<p>Twoja obecna ocena: <strong>{{ $userRating->rating }} ★</strong></p>
<!-- Przycisk rozwijający edycję -->
<button class="btn btn-primary"
    data-bs-toggle="collapse"
    data-bs-target="#editRatingForm"
    aria-expanded="false"
    aria-controls="editRatingForm">
    Edytuj ocenę
</button>

<div class="collapse mt-3" id="editRatingForm">
    <form action="{{ route('ratings.update', $userRating->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="rating" class="form-label">Ocena (1–5):</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">Wybierz ocenę</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $userRating->rating == $i ? 'selected' : '' }}>
                    {{ $i }} ★
                    </option>
                    @endfor
            </select>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">Zapisz zmiany</button>
            <button class="btn btn-secondary"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#editRatingForm">
                Anuluj
            </button>
        </div>
    </form>
</div>
@endif
@endauth



<h4 class="mt-5">Komentarze</h4>
@auth
<form action="{{ route('comments.store', $recipe->id) }}" method="POST" class="mt-3">
    @csrf
    <button class="btn btn-success mb-3"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#addCommentForm"
        aria-expanded="false"
        aria-controls="addCommentForm">
        Dodaj komentarz
    </button>

    <div class="collapse" id="addCommentForm">
        <form action="{{ route('comments.store', $recipe->id) }}" method="POST" class="mt-3">
            @csrf
            <div class="mb-3">
                <label for="comment" class="form-label">Treść komentarza</label>
                <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Wyślij</button>
        </form>
    </div>
</form>
@endauth
@guest
<p class="mt-3"><a href="{{ route('login') }}">Zaloguj się</a>, aby dodać komentarz.</p>
@endguest

@if ($recipe->comments->isEmpty())
<p>Brak komentarzy.</p>
@else
@foreach ($recipe->comments->sortByDesc('created_at') as $comment)
<div class="border rounded p-3 mt-3" id="comment-{{ $comment->id }}">
    <strong>{{ $comment->user->name ?? 'Anonim' }}</strong>
    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
    @if ($comment->updated_at && $comment->updated_at->gt($comment->created_at))
    <small class="text-muted">(edytowano)</small>
    @endif

    <p class="mb-0 comment-content">{{ $comment->comment }}</p>

    @if (Auth::check() && Auth::id() === $comment->user_id)
    <div class="mt-2">
        <!-- Przycisk Edytuj -->
        <button class="btn btn-sm btn-primary"
            data-bs-toggle="collapse"
            data-bs-target="#editCommentForm-{{ $comment->id }}">
            Edytuj
        </button>
        <!-- Przycisk Usuń -->
        <form id="delete-comment-{{ $comment->id }}" action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger"
                type="submit"
                onclick="return confirm('Na pewno chcesz usunąć ten komentarz?');">
                Usuń
            </button>
        </form>
    </div>

    <!-- Form do edycji w collapse Bootstrapa -->
    <div class="collapse mt-2" id="editCommentForm-{{ $comment->id }}">
        <div class="card card-body">
            <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-2">
                    <textarea name="comment" class="form-control" rows="3" required>{{ $comment->comment }}</textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success">Zapisz</button>
                    <button class="btn btn-secondary"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#editCommentForm-{{ $comment->id }}">
                        Anuluj
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endforeach
@endif
@endsection