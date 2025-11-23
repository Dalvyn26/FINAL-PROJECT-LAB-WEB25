<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $totalQuota = 12;  // Default annual leave quota
        $remainingQuota = $user->leave_quota;
        $usedQuota = $totalQuota - $remainingQuota;

        return view('profile.edit', [
            'user' => $user,
            'totalQuota' => $totalQuota,
            'remainingQuota' => $remainingQuota,
            'usedQuota' => $usedQuota,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Get validated data
        $data = $request->validated();

        // Handle password filtering - only hash if password is provided/filled
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Remove password from data if not provided to avoid setting null
            unset($data['password']);
        }

        // Handle avatar upload if present
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Delete old avatar if exists
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }

            // CRITICAL STEP: Add the avatar path to the validated data array
            $data['avatar'] = $avatarPath;
        }
        // If no avatar is uploaded, don't touch the avatar field at all - leave it unchanged

        // Apply updates to user
        $user->fill($data);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
