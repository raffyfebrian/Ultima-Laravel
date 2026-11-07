<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(15);
        $roles = Role::where('guard_name', 'web')->orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(6)],
            'role' => ['required', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->assignRole($data['role']);

        return back()->with('success', 'Pengguna '.$user->name.' berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::where('guard_name', 'web')->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::min(6)],
            'role' => ['required', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
        $user->syncRoles([$data['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna '.$user->name.' berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->hasRole('superadmin') && User::role('superadmin')->count() <= 1) {
            return back()->with('error', 'Superadmin terakhir tidak dapat dihapus.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
