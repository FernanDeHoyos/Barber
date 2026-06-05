<?php

namespace Database\Seeders;

use App\Models\Barber;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barber::create([
            'tenant_id' => 1,
            'name' => 'Carlos',
        ]);

        Barber::create([
            'tenant_id' => 1,
            'name' => 'Juan',
        ]);
    }
}
