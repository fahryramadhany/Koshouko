<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->where('role_id', '!=', 1) // Exclude super admin
            ->paginate(15);

        return view('admin.users.index', ['users' => $users]);
    }

    public function create()
    {
        $roles = \App\Models\Role::where('name', '!=', 'admin')->get();
        return view('admin.users.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['member_id'] = 'MBR' . date('YmdHis');
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = \App\Models\Role::where('name', '!=', 'admin')->get();
        return view('admin.users.edit', ['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->input('password'));
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    // User Reports - ONLY for Librarian (read-only)
    public function reports()
    {
        $users = User::with('role')
            ->where('role_id', '!=', 1) // Exclude super admin
            ->paginate(15);

        return view('admin.users.reports', ['users' => $users]);
    }
}
