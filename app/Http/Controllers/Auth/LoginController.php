<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\GuestSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Sapaan sekali tampil di halaman pertama setelah login (notifikasi di layout admin).
            return redirect()->intended(route('admin.dashboard'))->with('welcome', [
                'pending_reviews' => Article::where('status', 'pending_review')->count(),
                'pending_submissions' => GuestSubmission::where('status', 'pending')->count(),
            ]);
        }

        return back()->withErrors([
            'email' => 'Kredensial email atau password yang dimasukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
