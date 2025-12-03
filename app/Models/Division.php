<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    /** @use HasFactory<\Database\Factories\DivisionFactory> */
    use HasFactory;

    protected $fillable = ['title', 'slug'];

    public function districts() {
        return $this->hasMany(District::class);
    }

    public function thanas() {
        return $this->hasMany(Thana::class);
    }
}
