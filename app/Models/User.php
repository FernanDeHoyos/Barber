<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;

class User extends Authenticatable
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
