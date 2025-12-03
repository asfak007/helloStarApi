<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCompletionLog extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceCompletionLogFactory> */
    use HasFactory;

    protected $fillable = ['order_id','completed_by','completion_time','notes'];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function completedBy() {
        return $this->belongsTo(User::class,'completed_by');
    }
}
