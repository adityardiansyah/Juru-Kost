<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'tenants'])->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $tenants = Tenant::all();
        return view('admin.users.create', compact('roles', 'tenants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_superuser' => 'boolean',
            'tenant_access' => 'array',
            'tenant_access.*.tenant_id' => 'exists:tenants,id',
            'tenant_access.*.role_id' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_superuser' => $request->boolean('is_superuser'),
        ]);

        if (isset($validated['tenant_access'])) {
            foreach ($validated['tenant_access'] as $access) {
                if (!empty($access['tenant_id']) && !empty($access['role_id'])) {
                    $user->tenants()->attach($access['tenant_id'], ['role_id' => $access['role_id']]);
                }
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $tenants = Tenant::all();
        return view('admin.users.edit', compact('user', 'roles', 'tenants'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_superuser' => 'boolean',
            'tenant_access' => 'array',
            'tenant_access.*.tenant_id' => 'exists:tenants,id',
            'tenant_access.*.role_id' => 'exists:roles,id',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_superuser' => $request->boolean('is_superuser'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // Sync tenant access
        $user->tenants()->detach();
        if (isset($validated['tenant_access'])) {
            foreach ($validated['tenant_access'] as $access) {
                if (!empty($access['tenant_id']) && !empty($access['role_id'])) {
                    $user->tenants()->attach($access['tenant_id'], ['role_id' => $access['role_id']]);
                }
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus diri sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
