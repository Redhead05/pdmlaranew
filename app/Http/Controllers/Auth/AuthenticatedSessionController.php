<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route as RouteFacade;
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

    /**
     * Handle an incoming authentication request.
     * Return RedirectResponse or Response (HTML) when forcing client redirect for Turbo.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(LoginRequest $request): RedirectResponse|Response
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
            Log::info('Login failed attempt', ['email' => $credentials['email']]);
            return $this->sendFailedLoginResponse($request);
        }

        $request->session()->regenerate();

        Log::info('Login successful', ['user_id' => Auth::id(), 'email' => Auth::user()->email]);

        // Ambil intended (jika ada) lalu tarik dari session
        $intended = session()->pull('url.intended', null);
        $target = null;
        if ($intended) {
            // jika intended mengarah ke /login atau /logout, abaikan
            if (! str_contains($intended, '/login') && ! str_contains($intended, '/logout')) {
                Log::info('Redirecting to intended', ['intended' => $intended]);
                $target = $intended;
            }
        }

        $user = Auth::user();

        // fallback role-based jika target belum ditentukan
        if (! $target) {
            if ($user->hasRole('admin') && RouteFacade::has('admin.dashboard')) {
                $target = route('admin.dashboard');
            } elseif ($user->hasRole('adminlanding') && RouteFacade::has('adminlanding.dashboard')) {
                $target = route('adminlanding.dashboard');
            } elseif ($user->hasRole('asesor') && RouteFacade::has('asesor.dashboard')) {
                $target = route('asesor.dashboard');
            } elseif ($user->hasRole('user') && RouteFacade::has('dashboard.user')) {
                $target = route('dashboard.user');
            } else {
                $target = RouteServiceProvider::HOME;
            }
        }

        // Jika permintaan berasal dari Turbo (Hotwire), beberapa versi Turbo tidak mengikuti redirect
        // walau server mengirim 302. Kita deteksi header x-turbo-request-id dan kirim small HTML yang
        // memaksa redirect di sisi klien.
        if ($request->header('x-turbo-request-id')) {
            $escaped = e($target);
            $html = '<!doctype html><meta charset="utf-8"><title>Redirecting...</title>' .
                '<script>window.location.replace("' . $escaped . '");</script>';
            return response($html, 200)->header('Content-Type', 'text/html');
        }

        return redirect()->to($target);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
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


}
