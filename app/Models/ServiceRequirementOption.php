<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequirementOption extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRequirementOptionFactory> */
    use HasFactory;

    protected $fillable = ['service_requirement_id','requirement_icon','requirement_title'];

    public function requirement() {
        return $this->belongsTo(ServiceRequirement::class,'service_requirement_id');
    }
}
