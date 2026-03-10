<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'  => ['required', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'suffix'      => ['nullable', 'string', 'max:50'],
            'username'    => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'email'       => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'sex'         => ['nullable', 'in:male,female'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'username.required'   => 'Username is required.',
            'username.unique'     => 'This username is already taken.',
            'email.required'      => 'Email address is required.',
            'email.unique'        => 'This email is already in use.',
            'email.email'         => 'Please enter a valid email address.',
        ];
    }
}