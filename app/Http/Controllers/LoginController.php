<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();

            return redirect('/posts');
        }

        return back()->withErrors([
            'email' => 'Wrong email or password.',
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
