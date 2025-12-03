<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'payment_group_id','order_id','amount','method','provider_response',
        'status','payment_for','transaction_id','paid_at'
    ];

    public function paymentGroup() {
        return $this->belongsTo(PaymentGroup::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
