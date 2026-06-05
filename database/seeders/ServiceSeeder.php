<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'tenant_id' => 1,
            'name' => 'Corte Clásico',
            'duration_minutes' => 30,
            'price' => 20000,
        ]);

        Service::create([
            'tenant_id' => 1,
            'name' => 'Corte + Barba',
            'duration_minutes' => 60,
            'price' => 35000,
        ]);
    }
}
