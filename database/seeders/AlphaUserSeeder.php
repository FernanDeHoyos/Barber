<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AlphaUserSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'alpha')->first();
        
        if (!$tenant) {
            $this->command->error("El tenant 'alpha' no existe. Ejecuta TenantSeeder primero.");
            return;
        }

        $role = Role::where('name', 'admin')->first();

        $user = User::updateOrCreate(
            ['email' => 'alpha@barber.com'],
            [
                'name' => 'Admin Alpha',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'tenant_id' => $tenant->id,
            ]
        );

        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        $this->command->info("Usuario alpha@barber.com creado/actualizado correctamente y asignado al tenant 'alpha'.");
    }
}
