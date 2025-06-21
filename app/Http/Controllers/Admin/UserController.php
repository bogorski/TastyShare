<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(['search' => $search]);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function edit(User $user)
    {
        // Możesz dodać zabezpieczenia, np. nie pozwalać edytować siebie lub innych adminów
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_admin' => 'required|boolean',
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został zaktualizowany.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_visible = false;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Przepis został ukryty.');
    }
}
