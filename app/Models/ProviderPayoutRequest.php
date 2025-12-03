<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderPayoutRequest extends Model
{
    /** @use HasFactory<\Database\Factories\ProviderPayoutRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'provider_id','amount','payout_account_id','status','admin_note','provider_note'
    ];

    public function provider() {
        return $this->belongsTo(User::class,'provider_id');
    }

    public function payoutAccount() {
        return $this->belongsTo(ProviderPayoutAccount::class,'payout_account_id');
    }

    public function payoutLogs() {
        return $this->hasMany(ProviderPayoutLog::class,'payout_request_id');
    }
}
