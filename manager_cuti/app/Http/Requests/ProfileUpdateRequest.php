<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        if ($user->role === 'admin') {
            // Admin users can update all fields
            return [
                'username' => ['nullable', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($user->id),
                ],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:500'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:3072'], // Max 3MB
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ];
        } else {
            // Non-admin users can update username, name, phone, address, avatar, and password
            return [
                'username' => ['nullable', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:500'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:3072'], // Max 3MB
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ];
        }
    }
}
