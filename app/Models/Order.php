<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id','payment_group_id','address_id','service_id','provider_id',
        'quantity','unit_price','total_price','partial_amount','due_amount',
        'payment_status','order_status','order_date','order_time','placed_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function paymentGroup() {
        return $this->belongsTo(PaymentGroup::class);
    }

    public function address() {
        return $this->belongsTo(UserAddress::class,'address_id');
    }

    public function provider() {
        return $this->belongsTo(User::class,'provider_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function providerActions() {
        return $this->hasMany(ProviderActionLog::class);
    }

    public function completionLogs() {
        return $this->hasMany(ServiceCompletionLog::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function providerEarnings() {
        return $this->hasMany(ProviderEarning::class,'order_id');
    }
}
