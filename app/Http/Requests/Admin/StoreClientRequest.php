<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'img' => ['required', 'string', 'max:255'],
            'extra' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
