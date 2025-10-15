<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Jika user ada tapi nonaktif dan password cocok -> pesan khusus
        $user = User::where('email', $credentials['email'])->first();
        if ($this->isInactiveWithValidPassword($user, $credentials['password'])) {
            return $this->respondInactive($request);
        }

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $request->boolean('remember'))) {
            return $this->sendFailedLoginResponse($request);
        }

        $request->session()->regenerate();

        // tandai sukses untuk view biasa
        $request->session()->flash('login_success', true);

        // route by role
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('asesor')) {
            return redirect()->route('asesor.dashboard');
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
    //addon
    private function isInactiveWithValidPassword(?User $user, string $password): bool
    {
        if (! $user) {
            return false;
        }

        return ! $user->is_active && Hash::check($password, $user->password);
    }

    /**
     * Respond when account is inactive.
     */
    private function respondInactive(Request $request)
    {
        $message = 'id anda di suspend';

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }

        throw ValidationException::withMessages([
            'email' => $message,
        ]);
    }

    /**
     * Standard failed login response.
     */
    private function sendFailedLoginResponse(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => trans('auth.failed')], 422);
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Redirect user based on role.
     */
    private function redirectToByRole(User $user): RedirectResponse
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->hasRole('asesor')) {
            return redirect()->route('dashboard.asesor');
        }

        if ($user->hasRole('user')) {
            return redirect()->route('dashboard.user');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

}
