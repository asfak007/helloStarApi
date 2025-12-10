<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    protected $fillable = [
        'code',
        'offer_id',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
    
}
