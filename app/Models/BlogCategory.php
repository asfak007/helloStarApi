<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    /** @use HasFactory<\Database\Factories\BlogCategoryFactory> */
    use HasFactory;

    protected $fillable = ['title','slug'];

    public function blogs() {
        return $this->hasMany(Blog::class);
    }

    public function interestedUsers() {
        return $this->hasMany(UserInterestBlogCategory::class,'blog_category_id');
    }
}
