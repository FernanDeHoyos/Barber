<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'barber_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active'
    ];

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }
}
