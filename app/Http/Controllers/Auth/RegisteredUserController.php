<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nia' => ['required', 'string', 'max:50', 'unique:users,nia'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,asesor,user'],
            'is_active' => ['nullable', 'boolean'],

            'address_home' => ['nullable', 'string'],
            'home_city' => ['nullable', 'string', 'max:100'],
            'address_work' => ['nullable', 'string'],
            'work_city' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:20'],
            'type_asesor' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'nia' => $validated['nia'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->detail()->create([
            'address_home' => $validated['address_home'] ?? null,
            'home_city' => $validated['home_city'] ?? null,
            'address_work' => $validated['address_work'] ?? null,
            'work_city' => $validated['work_city'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'type_asesor' => $validated['type_asesor'] ?? null,
        ]);

        $user->assignRole($validated['role']);

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
