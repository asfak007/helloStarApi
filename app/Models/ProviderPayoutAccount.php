<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderPayoutAccount extends Model
{
    /** @use HasFactory<\Database\Factories\ProviderPayoutAccountFactory> */
    use HasFactory;

    protected $fillable = [
        'provider_id','account_type','account_name','account_number',
        'bank_name','branch_name','routing_number','is_default'
    ];

    public function provider() {
        return $this->belongsTo(User::class,'provider_id');
    }

    public function payoutRequests() {
        return $this->hasMany(ProviderPayoutRequest::class,'payout_account_id');
    }
}
