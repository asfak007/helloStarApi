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
    public function getRequirementIconUrlAttribute()
    {

        $imagePath = $this->attributes['requirement_icon'] ?? null;


        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }


        return asset('assets/images/demo/default.png');
    }
}
