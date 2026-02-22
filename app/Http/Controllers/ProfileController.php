<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // If the user has role 'asesor', 'admin', or 'adminlanding', show the editable form
        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['asesor', 'admin', 'adminlanding'])) {
            return view('profile.edit', [
                'user' => $user,
            ]);
        }

        // Otherwise show a read-only profile view
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        // Update basic fields (excluding password and guarded attrs)
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Save user detail fields for asesor/admin if present
        // Build detail data but respect admin-controlled location_enabled flag:
        $existingDetail = $user->detail;

        // By default, use submitted values
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Normalize decimal separator if provided (comma -> dot) and cast to float if numeric
        foreach (['latitude','longitude'] as $k) {
            if ($$k !== null && is_string($$k)) {
                $$k = str_replace(',', '.', trim($$k));
                // remove any stray spaces
            }
            if ($$k !== null && is_numeric($$k)) {
                $$k = (float) $$k;
            }
        }

        // If a UserDetail exists and location editing is disabled by admin, preserve existing coords
        if ($existingDetail && isset($existingDetail->location_enabled) && !$existingDetail->location_enabled) {
            $latitude = $existingDetail->latitude;
            $longitude = $existingDetail->longitude;
        }

        $detailData = [
            'gender' => $request->input('gender'),
            'address_home' => $request->input('address_home'),
            'home_city' => $request->input('home_city'),
            'address_work' => $request->input('address_work'),
            'work_city' => $request->input('work_city'),
            'type_asesor' => $request->input('type_asesor'),
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        // Only call updateOrCreate if the UserDetail relation exists
        if (method_exists($user, 'detail')) {
            $user->detail()->updateOrCreate(
                [],
                $detailData
            );
        }

        // If the authenticated user is admin/adminlanding and role/is_active provided, update them
        $auth = auth()->user();
        if ($auth && method_exists($auth, 'hasAnyRole') && $auth->hasAnyRole(['admin','adminlanding'])) {
            if ($request->filled('role') && class_exists('\Spatie\Permission\Models\Role')) {
                try {
                    $user->syncRoles([$request->input('role')]);
                } catch (\Exception $e) {
                    // ignore role sync errors for now
                }
            }

            if ($request->has('is_active')) {
                $user->is_active = (bool) $request->input('is_active');
                $user->save();
            }
        }

        // Redirect to role-specific profile route if available
        $redirectRoute = null;
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('asesor') && RouteFacade::has('asesor.profile.edit')) {
                $redirectRoute = route('asesor.profile.edit');
            } elseif (($user->hasRole('admin') || $user->hasRole('adminlanding')) && RouteFacade::has('adminlanding.profile.edit')) {
                $redirectRoute = route('adminlanding.profile.edit');
            }
        }

        if (!$redirectRoute && RouteFacade::has('profile.edit')) {
            $redirectRoute = route('profile.edit');
        }

        // Fallback to home if nothing found
        if (!$redirectRoute) {
            $redirectRoute = url('/');
        }

        // Use a flash toast payload instead of the old 'profile-updated' status so the view
        // can show a Bootstrap toast with a friendly message.
        return Redirect::to($redirectRoute)->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Profil berhasil diperbarui.'
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
