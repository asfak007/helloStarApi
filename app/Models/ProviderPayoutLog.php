<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderPayoutLog extends Model
{
    /** @use HasFactory<\Database\Factories\ProviderPayoutLogFactory> */
    use HasFactory;

    protected $fillable = [
        'provider_id','earning_ids','payout_request_id','amount','method','payout_account_id','payout_status','transaction_id','payout_date'
    ];

    protected $casts = [
        'earning_ids' => 'array'
    ];

    public function provider() {
        return $this->belongsTo(User::class,'provider_id');
    }

    public function payoutRequest() {
        return $this->belongsTo(ProviderPayoutRequest::class,'payout_request_id');
    }

    public function payoutAccount() {
        return $this->belongsTo(ProviderPayoutAccount::class,'payout_account_id');
    }
}
