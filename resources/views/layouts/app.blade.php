<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasty Share</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container min-vh-100 d-flex flex-column">
        {{-- Nawigacja --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">Tasty Share</a>

                <div class="collapse navbar-collapse" id="navbarButtons">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        @guest
                        {{-- Dla niezalogowanych --}}
                        <li class="nav-item me-2">
                            <a href="{{ route('register.show') }}" class="btn btn-outline-primary">Rejestracja</a>
                        </li>
                        <li class="nav-item me-2">
                            <a href="{{ route('login.show') }}" class="btn btn-outline-secondary">Logowanie</a>
                        </li>
                        @endguest

                        @auth
                        {{-- Dla zalogowanych --}}
                        <li class="nav-item me-2 d-flex align-items-center">
                            <span class="me-2">Witaj, {{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item me-2">
                            <a href="{{ route('recipes.create') }}" class="btn btn-outline-success">Dodaj przepis</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-danger" type="submit">Wyloguj</button>
                            </form>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Główna zawartość strony --}}
        <main class="flex-grow-1">
            @yield('content')
        </main>

        {{-- Stopka --}}
        <footer class="text-center py-3 border-top mt-4">
            <small>&copy; {{ date('Y') }} Tasty Share. Wszelkie prawa zastrzeżone.</small>
        </footer>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>