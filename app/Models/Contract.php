<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $fillable = [
        'buyer_id',
        'farmer_id',
        'listing_id',
        'crop_name',
        'agreed_price',
        'price_per_unit',
        'agreed_quantity',
        'total_amount',
        'delivery_date',
        'delivery_location',
        'delivery_status',
        'payment_terms',
        'advance_percentage',
        'advance_amount',
        'final_payment',
        'contract_start_date',
        'contract_end_date',
        'additional_terms',
        'status',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
