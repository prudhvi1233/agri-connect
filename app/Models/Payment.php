<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Payment extends Model
{
    protected $fillable = [
        'contract_id',
        'transaction_id',
        'amount',
        'payment_method',
        'payment_type',
        'payment_date',
        'status',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function buyer(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Contract::class, 'id', 'id', 'contract_id', 'buyer_id');
    }
}
