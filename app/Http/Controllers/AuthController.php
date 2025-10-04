<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register Page
    public function showRegister()
    {
        return view('auth.register'); // <-- ye tumhara form file hai
    }

    // Register User
   public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'restaurant_name' => 'required',
        'phone' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'restaurant_name' => $request->restaurant_name,
        'phone' => $request->phone,
        'email' => $request->email,
        'password' => $request->password, // auto hash ho jayega
    ]);

    \Auth::login($user);

    return redirect('/dashboard');
}

    // Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login User
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }
}
