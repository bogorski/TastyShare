<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // if (!auth()->check() || !auth()->user()->is_admin) {
        //     abort(403, 'Brak dostępu');
        // }
        return view('admin.dashboard');
    }
}
