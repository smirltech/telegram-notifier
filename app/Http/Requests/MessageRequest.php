<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'token' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'buttons' => ['nullable', 'array'],
            'buttons.*.text' => ['required', 'string'],
            'buttons.*.url' => ['required', 'string'],
        ];
    }
}
