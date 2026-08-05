<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateComponenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('componentes', 'slug')->ignore($this->route('componente'))],
            'name' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'paths' => ['required', 'string'],
            'wide' => ['boolean'],
            'content' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
