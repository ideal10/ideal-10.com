<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEntityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('entities', 'slug')->ignore($this->route('entity'))],
            'name' => ['required', 'string', 'max:255'],
            'links' => ['array'],
            'links.*.id' => ['nullable', 'integer', 'exists:entity_links,id'],
            'links.*.name' => ['nullable', 'string', 'max:255'],
            'links.*.href' => ['nullable', 'url', 'max:2048'],
            'links.*.order' => ['nullable', 'integer', 'min:0'],
            'links.*.remove' => ['nullable', 'boolean'],
        ];
    }
}
