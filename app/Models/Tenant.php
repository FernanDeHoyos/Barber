<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'contact_email',
        'phone',
        'address',
        'opening_time',
        'closing_time',
        'is_active',
        'plan',
    ];


    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Relaciones
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function barbers()
    {
        return $this->hasMany(Barber::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Helpers
    public function getUrlAttribute(): string
    {
        return "http://{$this->slug}.localhost";
    }

    protected $casts = [
    'is_active' => 'boolean',
    'opening_time' => 'datetime:H:i',
    'closing_time' => 'datetime:H:i',
];

}