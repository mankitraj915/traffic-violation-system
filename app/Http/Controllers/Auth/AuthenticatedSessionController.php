<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $selectedRole = $request->input('role');

        // Verify the selected role matches the user's role
        if ($user->role !== $selectedRole) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Invalid role selection for this account.');
        }

        // Redirect based on role
        return redirect()->intended($selectedRole === 'admin' ? route('admin.disputes') : route('dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}