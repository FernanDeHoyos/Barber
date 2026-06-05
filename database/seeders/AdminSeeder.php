<?php

namespace Database\Seeders;

use App\Models\AdminLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();

        $roles = collect([
            ['name' => 'superadmin', 'title' => 'Super Administrador', 'description' => 'Acceso completo al sistema.'],
            ['name' => 'admin', 'title' => 'Administrador', 'description' => 'Gestiona la barbería y sus usuarios.'],
            ['name' => 'barber', 'title' => 'Barbero', 'description' => 'Acceso a su agenda y clientes.'],
        ])->map(function ($payload) use ($tenant) {
            return Role::firstOrCreate([
                'name' => $payload['name'],
            ], array_merge($payload, ['tenant_id' => $tenant?->id]));
        });

        $permissions = collect([
            ['name' => 'view-users', 'title' => 'Ver usuarios', 'description' => 'Puede ver la lista de usuarios del sistema.'],
            ['name' => 'manage-users', 'title' => 'Gestionar usuarios', 'description' => 'Puede crear, editar y borrar usuarios.'],
            ['name' => 'manage-roles', 'title' => 'Gestionar roles', 'description' => 'Puede crear y actualizar roles.'],
            ['name' => 'manage-permissions', 'title' => 'Gestionar permisos', 'description' => 'Puede definir permisos y asignarlos.'],
            ['name' => 'view-logs', 'title' => 'Ver logs', 'description' => 'Puede revisar el historial de acciones del sistema.'],
        ])->map(function ($payload) use ($tenant) {
            return Permission::firstOrCreate([
                'name' => $payload['name'],
            ], array_merge($payload, ['tenant_id' => $tenant?->id]));
        });

        $roles->firstWhere('name', 'superadmin')->permissions()->sync($permissions->pluck('id'));
        $roles->firstWhere('name', 'admin')->permissions()->sync($permissions->whereNotIn('name', ['manage-permissions'])->pluck('id'));
        $roles->firstWhere('name', 'barber')->permissions()->sync($permissions->whereIn('name', ['view-users'])->pluck('id'));

        $admin = User::firstOrCreate([
            'email' => 'admin@barbersaas.test',
        ], [
            'name' => 'Admin SaaS',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'tenant_id' => $tenant?->id,
        ]);

        $admin->roles()->syncWithoutDetaching([$roles->firstWhere('name', 'superadmin')->id]);
        $admin->permissions()->syncWithoutDetaching($permissions->pluck('id'));

        AdminLog::create([
            'user_id' => $admin->id,
            'event' => 'seeded_admin_account',
            'description' => 'Usuario administrativo inicial creado con permisos completos.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder',
            'metadata' => ['email' => $admin->email],
        ]);
    }
}
