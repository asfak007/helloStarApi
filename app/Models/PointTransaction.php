<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\PointTransactionFactory> */
    use HasFactory;

    protected $fillable = ['user_id','type','points','description'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
