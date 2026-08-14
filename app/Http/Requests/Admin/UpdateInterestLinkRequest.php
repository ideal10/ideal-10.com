<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateInterestLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link_type' => ['required', 'in:external,file'],
            'url' => ['required_if:link_type,external', 'nullable', 'url', 'max:2048'],
            'file' => ['nullable', File::types(['xlsx', 'xls', 'csv', 'pdf', 'doc', 'docx', 'zip'])->max('200mb')],
            'icon' => ['nullable', 'string', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
            'active' => ['boolean'],
        ];
    }
}
