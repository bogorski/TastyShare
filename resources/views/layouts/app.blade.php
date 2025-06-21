<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tasty Share</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>

<body class="d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
        <div class="container d-flex align-items-center">

            {{-- Brand po lewej --}}
            <a class="navbar-brand fw-bold me-4" href="{{ route('home') }}">Tasty Share</a>

            {{-- Lewe menu --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex flex-row">
                <li class="nav-item">
                    <a href="{{ route('recipes.index') }}" class="nav-link">Przepisy</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link">Kategorie</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('diettypes.index') }}" class="nav-link">Diety</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ingredients.index') }}" class="nav-link">Składniki</a>
                </li>
            </ul>

            {{-- Środkowy przycisk Panel admina --}}
            @auth
            @admin
            <div class="mx-auto">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-warning">Panel admina</a>
            </div>
            @endadmin
            @endauth

            {{-- Prawe menu --}}
            <ul class="navbar-nav ms-auto align-items-lg-center d-flex flex-row">
                @guest
                <li class="nav-item me-2">
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Rejestracja</a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Logowanie</a>
                </li>
                @endguest

                @auth
                <li class="nav-item me-3 d-none d-lg-block">
                    <span class="navbar-text text-muted">Witaj, <strong>{{ Auth::user()->name }}</strong></span>
                </li>

                <li class="nav-item me-2">
                    <a href="{{ route('recipes.mine') }}" class="nav-link">Moje przepisy</a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ route('comments.mine') }}" class="nav-link">Moje komentarze</a>
                </li>

                <li class="nav-item ms-2">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-danger" type="submit">Wyloguj</button>
                    </form>
                </li>
                @endauth
            </ul>

        </div>
    </nav>

    {{-- Główna zawartość strony --}}
    <main class="flex-grow-1 container py-4">
        @if(session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }
            }, 5000);
        </script>
        @endif

        @yield('content')
    </main>

    {{-- Stopka --}}
    <footer class="bg-light text-center text-muted py-3 border-top mt-auto">
        <div class="container">
            <small>&copy; {{ date('Y') }} Tasty Share. Wszelkie prawa zastrzeżone.</small>
        </div>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>