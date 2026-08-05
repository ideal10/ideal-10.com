<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['url' => '/', 'label' => 'Inicio', 'match' => ['/', '/index']],
            ['url' => '/nosotros', 'label' => '¿Quiénes somos?', 'match' => null],
            ['url' => '/ideal-10', 'label' => 'Ideal.10', 'match' => null],
            ['url' => '/enlaces_de_interes', 'label' => 'Enlaces de interés', 'match' => null],
        ];

        foreach ($items as $order => $item) {
            NavItem::query()->updateOrCreate(
                ['url' => $item['url']],
                ['label' => $item['label'], 'match' => $item['match'], 'order' => $order + 1]
            );
        }
    }
}
