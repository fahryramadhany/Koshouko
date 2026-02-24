<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')
            ->where('role_id', '!=', 1); // Exclude super admin

        // Allow filtering by role name (admin sees this filter in the UI)
        if ($request->filled('role') && in_array($request->role, ['pustakawan', 'member', 'all'])) {
            if ($request->role !== 'all') {
                $query->whereHas('role', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        $roles = \App\Models\Role::where('name', '!=', 'admin')->get();

        return view('admin.users.index', ['users' => $users, 'roles' => $roles]);
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
    public function reports(Request $request)
    {
        // Show only members to pustakawan
        $users = User::with('role')
            ->whereHas('role', function ($q) {
                $q->where('name', 'member');
            })
            ->orderBy('name')
            ->paginate(15);

        // Librarian view (read-only) - placed under pustakawan views
        return view('pustakawan.users.index', ['users' => $users]);
    }
}
