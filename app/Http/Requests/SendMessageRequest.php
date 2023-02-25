<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
            'token' => ['required', 'string'],
            'message' => ['required', 'array'],
            'message.content' => ['required', 'string'],
            'message.buttons' => ['nullable', 'array'],
            'message.buttons.*.text' => ['required', 'string'],
            'message.buttons.*.url' => ['required', 'string'],
        ];
    }
}
