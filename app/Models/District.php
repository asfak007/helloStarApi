<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    /** @use HasFactory<\Database\Factories\DistrictFactory> */
    use HasFactory;

    protected $fillable = ['division_id','title','slug'];

    public function division() {
        return $this->belongsTo(Division::class);
    }

    public function thanas() {
        return $this->hasMany(Thana::class);
    }
}
