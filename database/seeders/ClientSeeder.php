<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'Alcaldia de Cubarral', 'img' => 'cubarral.png'],
            ['name' => 'Alcaldia de El Castillo', 'img' => 'castillo.png'],
            ['name' => 'Alcaldia de Cumaribo', 'img' => 'cumaribo.png'],
            ['name' => 'Alcaldia de San Juanito', 'img' => 'san-juanito.png'],
            ['name' => 'Alcaldia de Santa Rosalia', 'img' => 'santa-rosalia.png'],
            ['name' => 'Alcaldia de El Dorado', 'img' => 'dorado.png'],
            ['name' => 'Alcaldia de San Juan de Arama', 'img' => 'san-juan-arama.png'],
            ['name' => 'Alcaldia de Puerto Lleras', 'img' => 'puerto-lleras.png'],
            ['name' => 'EMPCA S.A ESP', 'img' => 'empca.png'],
            ['name' => 'Aguas de Rosalía E.S.P', 'img' => 'aguas-rosalia.png'],
            ['name' => 'Aguas de El Castillo E.S.P', 'img' => 'aguas-castillo.png'],
        ];

        $extraClients = [
            ['name' => 'Concejo de Cubarral', 'img' => 'concejo-cubarral.png'],
            ['name' => 'Personeria de Cubarral', 'img' => 'personeria-cubarral.png'],
        ];

        $order = 1;

        foreach ($clients as $client) {
            Client::query()->updateOrCreate(
                ['name' => $client['name']],
                ['img' => '/storage/clients/'.$client['img'], 'extra' => false, 'order' => $order++]
            );
        }

        foreach ($extraClients as $client) {
            Client::query()->updateOrCreate(
                ['name' => $client['name']],
                ['img' => '/storage/clients/'.$client['img'], 'extra' => true, 'order' => $order++]
            );
        }
    }
}
