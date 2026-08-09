<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportPhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'max:20', function ($attribute, $value, $fail) {
                $digits = preg_replace('/\D/', '', (string) $value);
                if (strlen($digits) < 7 || strlen($digits) > 10) {
                    $fail('El número debe tener entre 7 y 10 dígitos.');
                }
            }],
            'type' => ['required', 'in:whatsapp,dial'],
            'order' => ['nullable', 'integer', 'min:0'],
            'active' => ['boolean'],
        ];
    }
}
