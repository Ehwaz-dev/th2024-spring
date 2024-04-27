<?php

namespace App\Http\Requests\Api\Event;

use App\Models\Event;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'places' => ['array'],
            'places.*.type' => ['required', Rule::in(['region', 'city'])],
            'places.*.name' => ['required', 'string'],
            'tags' => ['required', 'array'],
            'tags.*' => [Rule::exists(Tag::class, 'id')],
            'requestToJoin' => ['required', 'boolean'],
            'status' => ['required', Rule::in(Event::STATUS_DONE, Event::STATUS_CANCEL)],
        ];
    }
}
