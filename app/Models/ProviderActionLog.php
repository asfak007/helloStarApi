<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderActionLog extends Model
{
    /** @use HasFactory<\Database\Factories\ProviderActionLogFactory> */
    use HasFactory;

    protected $fillable = ['order_id','provider_id','action','notes','action_time'];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function provider() {
        return $this->belongsTo(User::class,'provider_id');
    }
}
