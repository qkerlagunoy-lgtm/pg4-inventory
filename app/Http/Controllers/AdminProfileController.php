<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\ItemRequest;

class AdminProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();

        $recentActivity = ItemRequest::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('updated_at')
            ->limit(10)
            ->get();

        return view('admin.profile', compact('user', 'recentActivity'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'last_name'   => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix'      => 'nullable|string|max:20',
            'username'    => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'       => 'required|email|max:255|unique:users,email,' . $user->id,
            'sex'         => 'nullable|in:male,female',
        ]);

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated)->save();

        return redirect()->route('admin.profile.edit')
                         ->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        \DB::table('users')->where('id', $user->id)->update(['avatar' => $path]);

        Auth::setUser($user->fresh());

        return redirect()->route('admin.profile.edit')
                         ->with('status', 'avatar-updated');
    }
}