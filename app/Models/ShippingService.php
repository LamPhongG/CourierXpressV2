<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_fee',
        'fee_per_kg',
        'fee_per_km',
        'max_weight',
        'max_distance',
        'delivery_time',
        'is_active',
    ];

    protected $casts = [
        'base_fee' => 'decimal:2',
        'fee_per_kg' => 'decimal:2',
        'fee_per_km' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'max_distance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Quan hệ với orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}