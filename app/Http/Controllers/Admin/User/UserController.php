<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['detail', 'roles'])->orderBy('created_at', 'desc')->get();
        return view('menu.admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('menu.admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nia' => 'required|string|max:255|unique:users,nia',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'required|boolean',
            'role' => 'required|exists:roles,name',
            'gender' => 'required|in:L,P',
            'address_home' => 'nullable|string',
            'home_city' => 'nullable|string|max:255',
            'address_work' => 'nullable|string',
            'work_city' => 'nullable|string|max:255',
            'type_asesor' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'nia' => $validated['nia'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => $validated['is_active'],
            ]);

            // Create user detail
            $user->detail()->create([
                'gender' => $validated['gender'],
                'address_home' => $validated['address_home'] ?? null,
                'home_city' => $validated['home_city'] ?? null,
                'address_work' => $validated['address_work'] ?? null,
                'work_city' => $validated['work_city'] ?? null,
                'type_asesor' => $validated['type_asesor'] ?? null,
            ]);

            // Assign role
            $user->assignRole($validated['role']);

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['detail', 'roles']);
        return view('menu.admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load(['detail', 'roles']);
        return view('menu.admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nia' => 'required|string|max:255|unique:users,nia,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'required|boolean',
            'role' => 'required|exists:roles,name',
            'gender' => 'required|in:L,P',
            'address_home' => 'nullable|string',
            'home_city' => 'nullable|string|max:255',
            'address_work' => 'nullable|string',
            'work_city' => 'nullable|string|max:255',
            'type_asesor' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $userData = [
                'name' => $validated['name'],
                'nia' => $validated['nia'],
                'email' => $validated['email'],
                'is_active' => $validated['is_active'],
            ];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            // Update or create user detail
            $user->detail()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'gender' => $validated['gender'],
                    'address_home' => $validated['address_home'] ?? null,
                    'home_city' => $validated['home_city'] ?? null,
                    'address_work' => $validated['address_work'] ?? null,
                    'work_city' => $validated['work_city'] ?? null,
                    'type_asesor' => $validated['type_asesor'] ?? null,
                ]
            );

            // Sync role
            $user->syncRoles([$validated['role']]);

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Delete user detail
            $user->detail()->delete();

            // Delete user
            $user->delete();

            return redirect()->route('admin.user.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        try {
            $user->is_active = !$user->is_active;
            $user->save();

            return response()->json([
                'success' => true,
                'is_active' => $user->is_active,
                'message' => 'Status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
}
