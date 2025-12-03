<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderEarning extends Model
{
    /** @use HasFactory<\Database\Factories\ProviderEarningFactory> */
    use HasFactory;

    protected $fillable = ['order_id','provider_id','admin_commission','provider_amount','status'];

    public function provider() {
        return $this->belongsTo(User::class,'provider_id');
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function payoutLogs() {
        return $this->hasMany(ProviderPayoutLog::class);
    }
}
