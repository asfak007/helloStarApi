<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFaq extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFaqFactory> */
    use HasFactory;

    protected $fillable = ['service_id','serial_number','question','answer'];

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
