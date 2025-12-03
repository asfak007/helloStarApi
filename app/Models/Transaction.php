<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id','service_id','amount','user_id','card_type',
        'transaction_id','transaction_date','currency','ssl_status','amount_type'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
