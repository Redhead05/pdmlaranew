<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $request->boolean('remember'))) {
            // login gagal
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => trans('auth.failed')], 422);
            }
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // tandai sukses untuk view biasa
        $request->session()->flash('login_success', true);

        // jika request AJAX / JSON, kembalikan respons JSON
        if($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'user' => Auth::user()->only(['id','name','email']),
            ]);
        }

        // hormati url.intended bila ada
        $intended = $request->session()->pull('url.intended');
        if ($intended) {
            return redirect()->to($intended);
        }

        // route by role
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->hasRole('asesor')) {
            return redirect()->route('dashboard.asesor');
        } elseif ($user->hasRole('user')) {
            return redirect()->route('dashboard.user');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
