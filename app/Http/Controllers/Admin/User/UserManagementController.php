<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\PermissionAudit;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Matrix hak akses: semua user x semua permission menu (checkbox).
     */
    public function index()
    {
        $permissions = config('menu-permissions.menus');
        $users = User::query()
            ->with('permissions')
            ->orderBy('name')
            ->get();

        return view('menu.admin.user-management.index', compact('permissions', 'users'));
    }

    /**
     * Toggle satu permission untuk satu user (AJAX).
     * Body: user_id, permission, checked (0/1)
     */
    public function togglePermission(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'permission' => ['required', 'string'],
            'checked' => ['required', 'boolean'],
        ]);

        $validNames = collect(config('menu-permissions.menus'))
            ->pluck('permission')
            ->all();

        if (! in_array($data['permission'], $validNames, true)) {
            return response()->json(['success' => false, 'message' => 'Permission tidak valid.'], 422);
        }

        $actor = $request->user();

        // 1. Cegah self-edit (lockout / eskalasi diri)
        if ($data['user_id'] === $actor->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah hak akses diri sendiri.',
            ], 403);
        }

        $user = User::findOrFail($data['user_id']);

        // 2. Lindungi super-admin: user ber-role admin tidak bisa diubah via matrix.
        if ($user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Hak akses user dengan role admin tidak dapat diubah di sini.',
            ], 403);
        }

        // 3. Batas delegasi: hanya boleh grant/revoke permission yang dimiliki pelaku.
        if (! $actor->hasPermissionTo($data['permission'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah hak akses ini.',
            ], 403);
        }

        $action = $data['checked'] ? 'grant_permission' : 'revoke_permission';

        if ($data['checked']) {
            $user->givePermissionTo($data['permission']);
        } else {
            $user->revokePermissionTo($data['permission']);
        }

        PermissionAudit::create([
            'actor_id' => $actor->id,
            'user_id' => $user->id,
            'action' => $action,
            'value' => $data['permission'],
        ]);

        return response()->json([
            'success' => true,
            'message' => $data['checked'] ? 'Hak akses diberikan.' : 'Hak akses dicabut.',
        ]);
    }
}
