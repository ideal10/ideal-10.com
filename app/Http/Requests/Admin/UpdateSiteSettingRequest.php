<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_url' => ['required', 'url', 'max:255'],
            'lang' => ['required', 'string', 'max:10'],
            'title' => ['required', 'string', 'max:255'],
            'mailer' => ['required', 'url', 'max:255'],
        ];
    }
}
