<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'about' => ['nullable', 'string'],
            'name' => ['string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
