<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedTime extends Model
{
    protected $fillable = [
        'tenant_id',
        'barber_id',
        'date',
        'start_time',
        'end_time',
        'reason',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }
}
