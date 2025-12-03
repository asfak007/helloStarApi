<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProcess extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceProcessFactory> */
    use HasFactory;
    protected $fillable = ['service_id','serial_number','title','description','image'];

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
