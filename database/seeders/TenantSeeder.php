<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::create([
            'name' => 'Barbería Alpha',
            'slug' => 'alpha',
            'primary_color' => '#111827',
            'secondary_color' => '#D4AF37',
        ]);

        Tenant::create([
            'name' => 'Barbería Elite',
            'slug' => 'elite',
            'primary_color' => '#0F172A',
            'secondary_color' => '#F59E0B',
        ]);

        Tenant::firstOrCreate(
            ['slug' => 'demo'],
            [
                'name'            => 'Barbería Demo',
                'contact_email'   => 'demo@barberia.test',
                'phone'           => '3001234567',
                'address'         => 'Calle 10 #20-30, Medellín',
                'primary_color'   => '#0f0f11',
                'secondary_color' => '#d4af37',
                'accent_color'    => '#1a1a1f',
                'is_active'       => true,
                'plan'            => 'trial',
            ]
        );
    }
}