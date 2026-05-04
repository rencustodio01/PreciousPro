<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'full_name'      => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($userId)],
            'password'       => $userId
                ? ['nullable', 'confirmed', 'min:8', Password::min(8)->letters()->mixedCase()->numbers()->symbols()]
                : ['required', 'confirmed', 'min:8', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'role_id'        => ['required', 'exists:roles,id'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'birthday'       => ['nullable', 'date', 'before:today'],
            'birthplace'     => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required'       => 'Full name is required.',
            'email.required'           => 'Email address is required.',
            'email.email'              => 'Please enter a valid email address.',
            'email.unique'             => 'This email is already taken.',
            'password.required'        => 'Password is required.',
            'password.confirmed'       => 'Password confirmation does not match.',
            'password.min'             => 'Password must be at least 8 characters long.',
            'password.letters'         => 'Password must contain at least one letter.',
            'password.mixed'           => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers'         => 'Password must contain at least one number.',
            'password.symbols'         => 'Password must contain at least one special character (e.g., !@#$%^&*).',
            'birthday.before'          => 'Birthday must be a past date.',
            'role_id.required'         => 'Please assign a role.',
            'role_id.exists'           => 'The selected role does not exist.',
        ];
    }
}
