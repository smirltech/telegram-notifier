<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'file' => ['required', 'array'],
            'file.name' => ['nullable', 'string'],
            'file.path' => ['required', 'string'],
            'file.type' => ['nullable', 'string', 'in:photo,video,audio,animation,voice,video_note'],
            'buttons' => ['nullable', 'array'],
            'buttons.*.text' => ['required', 'string'],
            'buttons.*.url' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.type.in' => 'The file type must be one of the following: photo, video, audio, animation, voice, video_note.',
        ];
    }
}
