<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // sesuaikan view folder kamu
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', $request->role)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email, password, atau bidang salah.');
        }

        Auth::login($user);

        return redirect()->intended('/dashboard'); // arahkan ke dashboard setelah login
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    
}
