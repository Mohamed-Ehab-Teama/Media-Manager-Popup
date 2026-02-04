<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
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
            'file'      => [
                'required',
                'file',
                'max:5120',     // 5 * 1024 = 5120 => 5MB
                'mimes:png,jpg,jpeg,pdf',
            ],
            'title'     => 'nullable|string|max:255',
            'alt'       => 'nullable|string',
            'caption'   => 'nullable|string',
            // 'title'     => '',
            // 'alt'       => '',
            // 'caption'   => '',
            // 'disk'      => '',
            // 'path'      => '',
            // 'url'       => '',
            // 'width'     => '',
            // 'height'    => '',
            // 'mime_type' => '',
            // 'size'      => '',
        ];
    }
}
