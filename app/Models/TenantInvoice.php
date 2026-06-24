<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantInvoice extends Model
{
    protected $fillable = [
        'tenant_id',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'reference',
        'transaction_id',
        'payment_method',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
