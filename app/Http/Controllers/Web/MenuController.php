<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['parent', 'children', 'roles'])
            ->root()
            ->ordered()
            ->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = Menu::root()->ordered()->get();
        return view('admin.menus.form', [
            'menu' => new Menu(),
            'parentMenus' => $parentMenus,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'icon_svg' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'requires_superuser' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['requires_superuser'] = $request->has('requires_superuser');

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $parentMenus = Menu::root()->where('id', '!=', $menu->id)->ordered()->get();
        return view('admin.menus.form', [
            'menu' => $menu,
            'parentMenus' => $parentMenus,
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'icon_svg' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'requires_superuser' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['requires_superuser'] = $request->has('requires_superuser');

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diupdate!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus!');
    }

    public function assignRoles(Menu $menu)
    {
        $roles = Role::all();
        $assignedRoleIds = $menu->roles->pluck('id')->toArray();

        return view('admin.menus.assign-roles', compact('menu', 'roles', 'assignedRoleIds'));
    }

    public function updateRoles(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $menu->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Role assignment berhasil diupdate!');
    }
}
