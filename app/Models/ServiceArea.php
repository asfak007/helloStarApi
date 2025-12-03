<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceAreaFactory> */
    use HasFactory;

    protected $fillable = ['service_id','thana_id','division_id','country'];

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function thana() {
        return $this->belongsTo(Thana::class);
    }

    public function division() {
        return $this->belongsTo(Division::class);
    }
}
