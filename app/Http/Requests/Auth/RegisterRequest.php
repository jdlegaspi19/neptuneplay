<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'     => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users,name'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
            'captcha_key'  => ['required', 'string'],
            'captcha_code' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex'  => 'Username can only contain letters, numbers, and underscores.',
            'username.unique' => 'This username is already taken.',
            'email.unique'    => 'This email is already registered.',
        ];
    }
}
