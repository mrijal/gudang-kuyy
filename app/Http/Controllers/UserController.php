<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view-user')) {
            abort(403);
        }
        $data = $this->data;
        $data['users'] = User::all();
        return view('main.user.list-user', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('add-user')) {
            abort(403);
        }
        $data = $this->data;
        $roles = Role::all();
        $data['roles'] = $roles;
        return view('main.user.add-user', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add-user')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
        ]);

        $is_active = $request->is_active ? 1 : 0;
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->is_active = $is_active;
        $user->save();

        // check role name validity 
        $role = Role::findByName($request->role);
        if (!$role) {
            return redirect()->back()->withErrors(['role' => 'Terjadi Error saat memilih Role.']);
        }

        // assign role
        // delete old role if exists
        $user->syncRoles($request->role);

        return redirect('user')->with('success', 'User Baru Berhasil Dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('edit-user')) {
            abort(403);
        }
        $data = $this->data;
        $user = User::find($id);
        $roles = Role::all();
        $data['user'] = $user;
        $data['roles'] = $roles;
        return view('main.user.edit-user', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('edit-user')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ]);


        $is_active = $request->is_active ? 1 : 0;

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = $is_active;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // check is role changed
        $currentRole = $user->roles->first()->name;
        if ($currentRole !== $request->role) {
            // check role name validity 
            $role = Role::findByName($request->role);
            if (!$role) {
                return redirect()->back()->withErrors(['role' => 'Terjadi Error saat memilih Role.']);
            }

            // remove old role and assign new role
            $user->removeRole($currentRole);
            $user->assignRole($request->role);
        }

        return redirect('user')->with('success', 'User Berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('delete-user')) {
            abort(403);
        }
        $user = User::find($id);
        $user->delete();
        return redirect('user')->with('success', 'User Berhasil dihapus.');
    }
}
