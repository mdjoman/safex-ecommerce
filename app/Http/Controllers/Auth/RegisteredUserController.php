<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Check if user is authenticated (Breeze v2.4)
        if (!Auth::check()) {
            abort(403, 'Unauthorized - Please login to access registration.');
        }

        // Optional: Restrict to admin/staff only
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'You do not have permission to register new users.');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            abort(403, 'Unauthorized - Please login to register new users.');
        }

        // Optional: Restrict to admin/staff only
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'You do not have permission to register new users.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['sometimes', 'string', 'in:admin,staff,user'], // Optional for Breeze
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'admin', // Add role if you have it
        ]);

        event(new Registered($user));

        // Breeze v2.4 - Don't log in the user, redirect to admin instead
        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} created successfully!");
    }
}
