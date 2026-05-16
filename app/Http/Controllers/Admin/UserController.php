<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create() { return view('admin.users.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6|confirmed',
            'role'                  => 'required|in:admin,kasir',
        ]);
        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = true;
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user) { return view('admin.users.edit', compact('user')); }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'role'      => 'required|in:admin,kasir',
            'password'  => 'nullable|min:6|confirmed',
            'is_active' => 'boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User dihapus.');
    }
}
