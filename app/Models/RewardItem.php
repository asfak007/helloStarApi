<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardItem extends Model
{
    /** @use HasFactory<\Database\Factories\RewardItemFactory> */
    use HasFactory;

    protected $fillable = [
        'title','description','promo_code','min_spend','discount_amount','cost_points','status'
    ];

    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }
}
