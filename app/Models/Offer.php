<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'service_id',
        'title',
        'description',
        'discount_amount',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
