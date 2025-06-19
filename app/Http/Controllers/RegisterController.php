<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Pokaż formularz rejestracji
    public function show()
    {
        return view('auth.register');
    }

    // Obsłuż rejestrację
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Stwórz użytkownika
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Zaloguj użytkownika (opcjonalnie)
        Auth::login($user);


        // Przekieruj gdzie chcesz, np. na home
        return redirect()->route('home')->with('success', 'Rejestracja zakończona sukcesem!');
    }
}
