<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGroup extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentGroupFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id','subtotal','discount','delivery_fee','platform_fee','total',
        'payment_status','payment_type','promo_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function promo() {
        return $this->belongsTo(RewardItem::class,'promo_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
