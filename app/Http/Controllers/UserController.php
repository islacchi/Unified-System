<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("role = 'admin' DESC")->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,staff',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

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
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role'  => 'required|in:admin,staff',
    ];

    // Only validate password if provided
    if ($request->filled('password')) {
        $rules['password'] = 'min:8|confirmed';
    }

    $data = $request->validate($rules);

    $user->name  = $data['name'];
    $user->email = $data['email'];
    $user->role  = $data['role'];

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

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
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:8|confirmed';
        }

        $data = $request->validate($rules);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')
            ->with('message', 'Profile updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();
        return back()->with('message', "User deleted.");
    }
}