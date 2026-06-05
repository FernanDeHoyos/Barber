<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function barbers()
    {
        return $this->belongsToMany(Barber::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
