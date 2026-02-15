<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('id') ? \App\Models\User::find($this->route('id')) : null;

        if (!$user) {
            return false;
        }

        // User can update their own profile, or admin can update any
        return $this->user()->id === $user->id || $this->user()->user_type === 'admin';
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $userId],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'phone' => ['sometimes', 'string', 'max:15'],
            'address' => ['sometimes', 'string', 'max:500'],
            'pincode' => ['sometimes', 'string', 'size:6'],
            'profile_image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'user_type' => ['sometimes', 'in:admin,user,seller'],
        ];
    }
}
