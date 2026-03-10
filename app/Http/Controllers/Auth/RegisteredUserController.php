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
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'suffix'      => ['nullable', 'string', 'max:50'],
            'username'    => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'sex'         => ['required', 'in:male,female'],
            'unit'        => ['required', 'string', 'max:50'],
            'category_id' => ['nullable', 'integer'],
        ]);

        $user = User::create([
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'suffix'      => $request->suffix,
            'username'    => $request->username,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'sex'         => $request->sex,
            'unit'        => $request->unit,
            'category_id' => $request->category_id,
            'is_active'   => false, // INACTIVE until admin activates
            'type'        => 'user',
        ]);

        event(new Registered($user));

        // Do NOT login — redirect to pending page
        return redirect()->route('register.pending');
    }
}