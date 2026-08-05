<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    public function run(): void
    {
        $entities = [
            'vichada' => [
                'name' => 'Gobernación del Vichada',
                'links' => [
                    ['name' => 'Gobernación', 'href' => 'http://vichada.ideal-10.com/'],
                    ['name' => 'Contraloría', 'href' => 'http://contraloria-vichada.ideal-10.com/'],
                    ['name' => 'Asamblea', 'href' => 'http://asamblea-vichada.ideal-10.com/'],
                    ['name' => 'IDER', 'href' => 'http://ider-vichada.ideal-10.com/'],
                    ['name' => 'Regalias', 'href' => 'http://regalias-vichada.ideal-10.com'],
                ],
            ],
            'calvario' => [
                'name' => 'Alcaldia del Calvario',
                'links' => [
                    ['name' => 'Alcaldia', 'href' => 'http://elcalvario.ideal-10.com/'],
                    ['name' => 'Concejo', 'href' => 'http://elcalvarioc.ideal-10.com/'],
                    ['name' => 'Personeria', 'href' => 'http://elcalvariop.ideal-10.com/'],
                    ['name' => 'Regalias', 'href' => 'http://elcalvarior.ideal-10.com/'],
                    ['name' => 'Servicios publicos', 'href' => 'http://elcalvariosp.ideal-10.com/'],
                ],
            ],
        ];

        foreach ($entities as $slug => $data) {
            $entity = Entity::query()->updateOrCreate(['slug' => $slug], ['name' => $data['name']]);

            foreach ($data['links'] as $order => $link) {
                $entity->links()->updateOrCreate(
                    ['name' => $link['name']],
                    ['href' => $link['href'], 'order' => $order + 1]
                );
            }
        }
    }
}
