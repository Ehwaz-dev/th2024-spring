<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users', 'max:254'],
            'password' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
