<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntityRequest extends FormRequest
{
    public const LOGO_RULES = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Sub-entity links may be typed without a scheme ("cubarral.ideal-10.com");
     * add https:// so they validate and work as absolute links.
     */
    protected function prepareForValidation(): void
    {
        $links = $this->input('links');

        if (! is_array($links)) {
            return;
        }

        foreach ($links as $key => $row) {
            if (is_array($row) && is_string($row['href'] ?? null)) {
                $links[$key]['href'] = self::normalizeUrl($row['href']);
            }
        }

        $this->merge(['links' => $links]);
    }

    public static function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '' || preg_match('#^[a-z][a-z0-9+.-]*://#i', $url)) {
            return $url;
        }

        return 'https://'.ltrim($url, '/');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],
            'logo' => self::LOGO_RULES,
            'remove_logo' => ['nullable', 'boolean'],
            'links' => ['array'],
            'links.*.id' => ['nullable', 'integer'],
            'links.*.name' => ['required', 'string', 'max:255'],
            'links.*.href' => ['required', 'url', 'max:2048'],
            'links.*.client_id' => ['nullable', 'integer', 'exists:clients,id'],
            'links.*.logo' => self::LOGO_RULES,
            'links.*.remove_logo' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'logo' => 'logo',
            'links.*.name' => 'nombre de la subentidad',
            'links.*.href' => 'enlace de la subentidad',
            'links.*.logo' => 'logo de la subentidad',
        ];
    }

    public function messages(): array
    {
        return [
            'logo.mimes' => 'El logo debe ser una imagen JPG, PNG o WEBP.',
            'logo.max' => 'El logo no puede pesar más de 2 MB.',
            'links.*.logo.mimes' => 'El logo debe ser una imagen JPG, PNG o WEBP.',
            'links.*.logo.max' => 'El logo no puede pesar más de 2 MB.',
            'links.*.href.url' => 'El enlace no es válido. Escribe una dirección como cubarral.ideal-10.com',
        ];
    }
}
