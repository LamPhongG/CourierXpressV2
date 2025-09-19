<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'address_line',
        'district',
        'city',
        'latitude',
        'longitude',
        'is_default'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_address'];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pickup orders for the address.
     */
    public function pickup_orders()
    {
        return $this->hasMany(Order::class, 'pickup_address_id');
    }

    /**
     * Get the delivery orders for the address.
     */
    public function delivery_orders()
    {
        return $this->hasMany(Order::class, 'delivery_address_id');
    }

    /**
     * Get the full address.
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address_line}, {$this->district}, {$this->city}";
    }
}
