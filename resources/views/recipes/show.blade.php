@extends('layouts.app')

@section('content')
<div class="container my-5">

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
                <h1 class="fw-bold mb-0">{{ $recipe->title }}</h1>

                @if ($isAuthor)
                <div>
                    <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-outline-primary btn-sm">Edytuj</a>
                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć ten przepis?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                    </form>
                </div>
                @endif
            </div>

            <div class="row">
                @if($recipe->image)
                <div class="col-6 mb-4">
                    <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" class="img-fluid rounded" />
                </div>
                @endif

                <div class="col-6 card no-transition mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Opis</h5>
                        <p class="card-text">{{ $recipe->description }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-5 card no-transition mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Składniki</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($recipe->ingredients as $ingredient)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 border-bottom py-2">
                                <span class="fw-semibold">{{ $ingredient->name }}</span>
                                <span class="text-muted small">
                                    {{ $ingredient->pivot->quantity }} {{ $ingredient->pivot->unit }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-7 card no-transition mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Instrukcje</h5>
                        <p class="card-text">{{ $recipe->instructions }}</p>
                    </div>
                </div>
            </div>
            <div class="row mb-4 text-center text-md-start">
                <div class="col-md-6 mb-3 mb-md-0">
                    <ul class="list-unstyled">
                        <li><strong>Czas przygotowania:</strong> {{ $recipe->preparation_time }} minut</li>
                        <li>
                            <strong>Kategoria:</strong>
                            @foreach ($recipe->categories as $category)
                            <span class="badge bg-danger me-1">{{ $category->name }}</span>
                            @endforeach
                        </li>
                        <li>
                            <strong>Dieta:</strong>
                            @foreach ($recipe->dietTypes as $dietType)
                            <span class="badge bg-success me-1">{{ $dietType->name }}</span>
                            @endforeach
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled text-md-end">
                        <li><strong>Dodany przez:</strong> {{ $recipe->user->name }}</li>
                        <li>
                            <strong>Średnia ocena:</strong>
                            @php
                            $avgRating = $recipe->ratings->avg('rating');
                            @endphp
                            {{ $avgRating ? number_format($avgRating, 1) . ' ★' : 'Brak ocen' }}
                            ({{ $recipe->comments->count() }} {{ polskaOdmiana('opinia', $recipe->comments->count()) }})
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Sekcja ocen --}}
            @auth
            @if ($isAuthor)
            <p class="text-muted fst-italic">Nie możesz ocenić własnego przepisu.</p>

            @elseif(!$userHasRated)
            <div class="card no-transition w-25 mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Dodaj ocenę</h5>
                    <form action="{{ route('ratings.store', $recipe->id) }}" method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <div>
                            <label for="rating" class="form-label">Ocena (1–5):</label>
                            <select name="rating" id="rating" class="form-select" required>
                                <option value="" selected disabled>Wybierz ocenę</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} ★</option>
                                    @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-semibold">Dodaj ocenę</button>
                    </form>
                </div>
            </div>
            @else
            <div class="card no-transition w-25 mb-4 shadow-sm">
                <div class="card-body">
                    <p class="mb-1 fw-bold">
                        Twoja obecna ocena:
                    </p>
                    <p>
                        <strong class="fs-3">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <=$userRating->rating)
                                <span style="color: #f5c518;">&#9733;</span> {{-- pełna gwiazdka --}}
                                @else
                                <span style="color: #ddd;">&#9733;</span> {{-- pusta gwiazdka --}}
                                @endif
                                @endfor
                        </strong>
                    </p>
                    <button class="btn btn-primary w-100 mb-3 fw-semibold"
                        data-bs-toggle="collapse"
                        data-bs-target="#editRatingForm"
                        aria-expanded="false"
                        aria-controls="editRatingForm">
                        Edytuj ocenę
                    </button>

                    <div class="collapse" id="editRatingForm">
                        <form action="{{ route('ratings.update', $userRating->id) }}" method="POST" class="d-flex flex-column gap-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="edit_rating" class="form-label">Ocena (1–5):</label>
                                <select name="rating" id="edit_rating" class="form-select" required>
                                    <option value="" disabled>Wybierz ocenę</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $userRating->rating == $i ? 'selected' : '' }}>
                                        {{ $i }} ★
                                        </option>
                                        @endfor
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success fw-semibold">Zapisz</button>
                                <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-toggle="collapse" data-bs-target="#editRatingForm">Anuluj</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endauth

            {{-- Komentarze --}}
            <h4 class="mb-3 fw-bold">Komentarze</h4>

            @auth
            <button class="btn btn-success mb-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#addCommentForm"
                aria-expanded="false"
                aria-controls="addCommentForm">
                Dodaj komentarz
            </button>

            <div class="collapse mb-4" id="addCommentForm">
                <div class="card no-transition shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('comments.store', $recipe->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="comment" class="form-label">Treść komentarza</label>
                                <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Wyślij</button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth

            @guest
            <p class="mb-4 text-muted">
                Aby dodać komentarz
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                    zaloguj się
                </a>
            </p>
            @endguest

            @if ($recipe->comments->isEmpty())
            <p class="text-muted">Brak komentarzy.</p>
            @else
            @foreach ($recipe->comments->sortByDesc('created_at') as $comment)
            <div class="card no-transition mb-3 shadow-sm" id="comment-{{ $comment->id }}">
                <div class="card-body ">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>{{ $comment->user->name ?? 'Anonim' }}</strong>
                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                            @if ($comment->updated_at && $comment->updated_at->gt($comment->created_at))
                            <small class="text-muted ms-2 fst-italic">(edytowano)</small>
                            @endif
                        </div>
                        @if (Auth::check() && Auth::id() === $comment->user_id)
                        <div>
                            <button class="btn btn-primary"
                                data-bs-toggle="collapse"
                                data-bs-target="#editCommentForm-{{ $comment->id }}"
                                aria-expanded="false"
                                aria-controls="editCommentForm-{{ $comment->id }}">
                                Edytuj
                            </button>
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Na pewno chcesz usunąć ten komentarz?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Usuń</button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <p class="mt-2 mb-0">{{ $comment->comment }}</p>

                    @if (Auth::check() && Auth::id() === $comment->user_id)
                    <div class="collapse mt-3" id="editCommentForm-{{ $comment->id }}">
                        <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <textarea name="comment" class="form-control" rows="3" required>{{ $comment->comment }}</textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success btn-sm me-1">Zapisz</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#editCommentForm-{{ $comment->id }}">Anuluj</button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection