<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Technicien;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = Technicien::factory()->administrateur()->create([
            'nom' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $techniciens = Technicien::factory(3)->create();
        $allTechniciens = $techniciens->push($admin);

        $clients = Client::factory(5)->create();

        foreach ($clients as $client) {
            Ticket::factory(3)->create([
                'client_id' => $client->id,
                'technicien_id' => $allTechniciens->random()->id,
            ]);
        }
    }
}
