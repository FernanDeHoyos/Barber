<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- IMPORTANTE
use Illuminate\Foundation\Auth\User as Authenticatable;                       // <-- IMPORTANTE
use Illuminate\Notifications\Notifiable;   // <-- IMPORTANTE
use Illuminate\Support\Collection;        // <-- IMPORTANTE

class User extends Authenticatable implements HasTenants // <-- IMPLEMENTAR LA INTERFAZ
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // =========================================================================
    // MÉTODOS REQUERIDOS POR FILAMENT (HAS TENANTS)
    // =========================================================================

    /**
     * Devuelve las barberías a las que este usuario tiene acceso para el menú desplegable.
     */
    public function getTenants(Panel $panel): Collection
    {
        // Si es superadmin, le cargamos TODAS las barberías existentes en el sistema
        if ($this->isSuperAdmin()) {
            return Tenant::all();
        }

        // Si es un admin común, solo le devolvemos la barbería que tiene asignada en su 'tenant_id'
        // Lo envolvemos en una colección porque Filament espera un grupo de registros
        return collect(filter_var($this->tenant, FILTER_DEFAULT) ? [$this->tenant] : []);
    }

    /**
     * Controla la seguridad a nivel de URL si intentan forzar el acceso a un Tenant.
     */
    public function canAccessTenant(Model $tenant): bool
    {
        // El superadmin puede saltar a cualquier barbería por URL sin restricciones
        if ($this->isSuperAdmin()) {
            return true;
        }

        // El admin común solo puede acceder si el ID de la barbería coincide con su 'tenant_id'
        return $this->tenant_id === $tenant->id;
    }

    // =========================================================================
    // RELACIONES Y LÓGICA EXISTENTE (SIN CAMBIOS)
    // =========================================================================

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role || $this->roles->contains('name', $role);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'superadmin') {
            return true;
        }

        if ($this->permissions->contains('name', $permission)) {
            return true;
        }

        return $this->roles->flatMap(fn ($role) => $role->permissions)->contains('name', $permission);
    }

    public function assignRole(Role|string $role): self
    {
        if (! $role instanceof Role) {
            $role = Role::firstWhere('name', $role);
        }

        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }

        return $this;
    }

    public function givePermissionTo(Permission|string $permission): self
    {
        if (! $permission instanceof Permission) {
            $permission = Permission::firstWhere('name', $permission);
        }

        if ($permission) {
            $this->permissions()->syncWithoutDetaching([$permission->id]);
        }

        return $this;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBarber(): bool
    {
        return $this->role === 'barber';
    }

    public function canAccessFilament(): bool
    {
        return $this->role === 'superadmin' || $this->role === 'admin';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
