<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeNavUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('El campo :attribute debe ser una ruta o una URL válida.');

            return;
        }

        if (str_starts_with($value, '/') && ! str_starts_with($value, '//')) {
            return;
        }

        if (in_array(parse_url($value, PHP_URL_SCHEME), ['http', 'https'], true)) {
            return;
        }

        $fail('El campo :attribute debe ser una ruta interna (por ejemplo /contacto) o una URL http(s).');
    }
}
