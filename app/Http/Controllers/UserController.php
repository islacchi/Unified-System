<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("role = 'admin' DESC")->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $defaultPassword = Setting::getValue('default_password', 'Staff@2024');
        return view('users.create', compact('defaultPassword'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,staff',
            'password' => 'required|min:8|confirmed',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $newUser = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'avatar'   => $avatarPath,
            'password' => $data['password'], // Cast auto-hashes
        ]);

        ActivityLog::log('user.created', $newUser);

        return redirect()->route('users.index')
            ->with('message', "User {$data['name']} created successfully.");
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'role'   => 'required|in:admin,staff',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:8|confirmed';
        }

        $data = $request->validate($rules);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->role  = $data['role'];

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $user->password = $request->password; // Cast auto-hashes
        }

        $user->save();

        ActivityLog::log('user.updated', $user);

        return redirect()->route('users.index')
            ->with('message', "{$user->name}'s account updated successfully.");
    }

    /**
     * Show the profile editor for the authenticated user.
     */
    public function profileEdit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the authenticated user's own profile (name, email, password).
     */
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:8|confirmed';
        }

        $data = $request->validate($rules);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $user->password = $request->password; // Cast auto-hashes
        }

        $user->save();

        return redirect()->back()
            ->with('message', 'Profile updated successfully.');
    }

    public function resetPassword(User $user)
    {
        $defaultPassword = Setting::getValue('default_password', 'Staff@2024');
        $user->password = $defaultPassword; // Cast will auto-hash
        $user->save();

        return redirect()->route('users.edit', $user)
            ->with('message', "Password for {$user->name} has been reset to the default password.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $label = $user->name;

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();
        ActivityLog::log('user.deleted', null, ['name' => $label], null, "Deleted user \"{$label}\"");
        return back()->with('message', "User deleted.");
    }
}