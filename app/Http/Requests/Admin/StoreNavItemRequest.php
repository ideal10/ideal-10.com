<?php

namespace App\Http\Requests\Admin;

use App\Rules\SafeNavUrl;
use Illuminate\Foundation\Http\FormRequest;

class StoreNavItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => ['required', 'string', 'max:255', new SafeNavUrl],
            'label' => ['required', 'string', 'max:255'],
            'match' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
