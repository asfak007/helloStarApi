<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['title','icon'];

    public function services() {
        return $this->hasMany(Service::class);
    }

    public function userCategories() {
        return $this->hasMany(UserServiceCategory::class);
    }

    public function getImageUrlAttribute()
    {

        $imagePath = $this->attributes['icon'] ?? null ;


        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }


        return asset('assets/images/demo/default.png');
    }

}
