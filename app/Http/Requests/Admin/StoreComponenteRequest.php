<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreComponenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'alpha_dash', 'max:255', 'unique:componentes,slug'],
            'name' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'paths' => ['required', 'string'],
            'wide' => ['boolean'],
            'content' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
