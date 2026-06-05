<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'tenant_id',
        'barber_id',
        'service_id',
        'customer_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
