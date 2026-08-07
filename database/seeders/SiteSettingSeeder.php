<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->updateOrCreate(['id' => 1], [
            'site_url' => 'https://ideal-10.com',
            'lang' => 'es-CO',
            'title' => 'Ideal',
            'web3forms_key' => '',
        ]);
    }
}
