<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // using spatie can() method
        if (!auth()->user()->can('view-role')) {
            abort(403);
        }
        $data = $this->data;
        $roles = Role::all();

        $data['roles'] = $roles;
        return view('main.role.list-role', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('add-role')) {
            abort(403);
        }
        $data = $this->data;
        // Dynamically fetch permissions
        $permissions = Permission::all();

        // Group permissions by categories (you can add a `category` field to the permissions model or use logic based on naming)
        $categories = [
            'Product' => $permissions->filter(fn($permission) => Str::endsWith($permission->name, 'product')),
            'Barang Masuk' => $permissions->filter(fn($permission) => Str::endsWith($permission->name, 'barang_masuk')),
            'Barang Keluar' => $permissions->filter(fn($permission) => Str::endsWith($permission->name, 'barang_keluar')),
            'Supplier' => $permissions->filter(fn($permission) => Str::endsWith($permission->name, 'supplier')),
            'User' => $permissions->filter(fn($permission) => Str::endsWith($permission->name, 'user')),
            'Opname' => $permissions->filter(fn($permission) => Str::endsWith($permission->name, 'opname')),
        ];

        $data['categories'] = $categories;
        return view('main.role.create-role', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add-role')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
        ]);

        // check if role already exists
        $role = Role::where('name', $request->name)->first();
        if ($role) {
            return redirect()->back()->withErrors(['name' => 'Role sudah ada.']);
        }

        Role::create(['name' => $request->name]);

        // check permissions submitted, handle if > 1 permission
        if ($request->has('permissions')) {
            $role = Role::findByName($request->name);
            $role->syncPermissions($request->permissions);
        }


        return redirect('role')->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->can('view-role')) {
            abort(403);
        }
        $data = $this->data;
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('-', $perm->name)[1] ?? 'Other';
        });

        $data['role'] = $role;
        $data['categories'] = $permissions;
        return view('main.role.detail-role', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('edit-role')) {
            abort(403);
        }
        $data = $this->data;
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('-', $perm->name)[1] ?? 'Other';
        });

        $data['role'] = $role;
        $data['categories'] = $permissions;
        return view('main.role.edit-role', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('edit-role')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        // check permissions submitted, handle if > 1 permission
        // syncPermissions will remove all permissions and attach the new ones
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect('role')->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('delete-role')) {
            abort(403);
        }
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect('role')->with('success', 'Role berhasil dihapus.');
    }

    public function generatePermission(Request $request)
    {
        if (!auth()->user()->can('add-permission')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
        ]);

        $name = $request->name;
        $permissions = [
            'add',
            'view',
            'edit',
            'delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $name . ' ' . $permission]);
        }

        return redirect()->back()->with('success', 'Permission berhasil dibuat.');
    }
}
