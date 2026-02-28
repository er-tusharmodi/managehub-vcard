<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use App\Models\Mongo\Role;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $admins = User::query()
            ->get()
            ->filter(function ($user) {
                $roles = $user->roles ?? [];
                return in_array('admin', $roles) || in_array('super-admin', $roles);
            })
            ->sortBy('name')
            ->values();

        return view('admin.admins.index', [
            'admins' => $admins,
        ]);
    }

    public function create(): View
    {
        return view('admin.admins.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['string'],
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $admin->syncRoles($validated['roles']);

        return redirect()->route('admin.admins.index')
            ->with('status', 'admin-created');
    }

    public function edit(User $admin): View
    {
        return view('admin.admins.edit', [
            'admin' => $admin,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['string'],
        ]);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $admin->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $admin->syncRoles($validated['roles']);

        return redirect()->route('admin.admins.index')
            ->with('status', 'admin-updated');
    }

    public function destroy(User $admin): RedirectResponse
    {
        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('status', 'admin-deleted');
    }
}
