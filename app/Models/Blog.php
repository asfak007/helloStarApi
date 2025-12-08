<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory;

    protected $fillable = ['blog_category_id','title','description','image'];

    public function category() {
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }
    

    public function getImageUrlAttribute()
    {

        $imagePath = $this->attributes['image'] ?? null || $this->attributes['reason_image'] ?? null  ;


        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }


        return asset('assets/images/demo/default.png');
    }
}
