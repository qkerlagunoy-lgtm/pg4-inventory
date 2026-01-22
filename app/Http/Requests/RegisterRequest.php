<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Name fields
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'suffix'      => ['nullable', 'string', 'max:50'],

            // Account info
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Profile
            'sex'         => ['required', 'in:male,female'],
            'unit'        => ['required', 'string', 'max:50'],
            'category_id' => ['nullable', 'integer'],
        ];
    }
}
