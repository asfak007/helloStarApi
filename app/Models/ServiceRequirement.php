<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequirement extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRequirementFactory> */
    use HasFactory;

    protected $fillable = ['service_id','description'];

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function options() {
        return $this->hasMany(ServiceRequirementOption::class);
    }
}
