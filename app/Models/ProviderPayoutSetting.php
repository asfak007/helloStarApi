<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderPayoutSetting extends Model
{
    /** @use HasFactory<\Database\Factories\ProviderPayoutSettingFactory> */
    use HasFactory;

    protected $fillable = ['provider_id','payout_method','weekly_day','monthly_date'];

    public function provider() {
        return $this->belongsTo(User::class,'provider_id');
    }
}
