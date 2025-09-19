<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'location',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the order that owns the tracking
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who updated the tracking
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}