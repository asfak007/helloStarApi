<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    /** @use HasFactory<\Database\Factories\UserRewardFactory> */
    use HasFactory;

    protected $fillable = ['user_id','reward_item_id','unique_code','used'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rewardItem()
    {
        return $this->belongsTo(RewardItem::class);
    }
}
