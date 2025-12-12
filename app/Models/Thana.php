<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    /** @use HasFactory<\Database\Factories\ThanaFactory> */
    use HasFactory;

    protected $fillable = ['division_id','district_id','name','slug'];

    public function division() {
        return $this->belongsTo(Division::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }
}
