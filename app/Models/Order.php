<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tracking_number',
        'user_id',
        'agent_id', 
        'shipper_id',
        'pickup_name',
        'pickup_phone',
        'pickup_address',
        'pickup_ward',
        'pickup_district', 
        'pickup_city',
        'pickup_latitude',
        'pickup_longitude',
        'delivery_name',
        'delivery_phone',
        'delivery_address',
        'delivery_ward',
        'delivery_district',
        'delivery_city',
        'delivery_latitude',
        'delivery_longitude',
        'package_type',
        'weight',
        'length',
        'width',
        'height',
        'value',
        'cod_amount',
        'notes',
        'shipping_service_id',
        'shipping_fee',
        'insurance_fee',
        'total_fee',
        'payment_method',
        'payment_status',
        'status',
        'confirmed_at',
        'assigned_at',
        'pickup_at',
        'picked_up_at',
        'in_transit_at',
        'delivering_at',
        'completed_at',
        'cancelled_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'confirmed_at' => 'datetime',
        'assigned_at' => 'datetime', 
        'pickup_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'in_transit_at' => 'datetime',
        'delivering_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'pickup_latitude' => 'decimal:8',
        'pickup_longitude' => 'decimal:8',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'value' => 'decimal:2',
        'cod_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'total_fee' => 'decimal:2',
    ];

    /**
     * Lấy customer của order.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias for customer relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lấy shipper của order.
     */
    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipper_id');
    }

    /**
     * Lấy agent xử lý order.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Lấy dịch vụ vận chuyển.
     */
    public function shippingService()
    {
        return $this->belongsTo(ShippingService::class);
    }

    /**
     * Get the status history for the order.
     */
    public function status_history()
    {
        return $this->hasMany(OrderStatus::class);
    }

    /**
     * Get the rating for the order.
     */
    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    /**
     * Get the order trackings for the order.
     */
    public function orderTrackings()
    {
        return $this->hasMany(OrderTracking::class)->orderBy('created_at', 'asc');
    }
}
