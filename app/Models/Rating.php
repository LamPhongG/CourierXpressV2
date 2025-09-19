<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'shipper_id',
        'rating',
        'comment',
        'delivery_rating',
        'communication_rating',
        'timeliness_rating'
    ];

    protected $casts = [
        'rating' => 'integer',
        'delivery_rating' => 'integer',
        'communication_rating' => 'integer',
        'timeliness_rating' => 'integer'
    ];

    /**
     * Get the order that was rated.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer who gave the rating.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the shipper who received the rating.
     */
    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipper_id');
    }
}