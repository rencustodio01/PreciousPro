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
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name'      => ['required', 'string', 'max:100'],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:users,email'],
            'contact_number' => ['required', 'string', 'max:20'],
            'birthday'       => ['required', 'date', 'before:today'],
            'birthplace'     => ['required', 'string', 'max:100'],
            'password'       => [
                'required',
                'confirmed',
                'min:8'
            ],
        ], [
            'full_name.required'       => 'Full name is required.',
            'email.required'           => 'Email address is required.',
            'email.email'              => 'Please enter a valid email address.',
            'email.unique'             => 'This email is already registered.',
            'contact_number.required'  => 'Contact number is required.',
            'birthday.required'        => 'Birthday is required.',
            'birthday.before'          => 'Birthday must be a past date.',
            'birthplace.required'      => 'Birthplace is required.',
            'password.required'        => 'Password is required.',
            'password.confirmed'       => 'Password confirmation does not match.',
            'password.min'             => 'Password must be at least 8 characters long.',
        ]);

        $user = User::create([
            'full_name'      => $request->full_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role_id'        => null,
            'contact_number' => $request->contact_number,
            'birthday'       => $request->birthday,
            'birthplace'     => $request->birthplace,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('pending');
    }
}
