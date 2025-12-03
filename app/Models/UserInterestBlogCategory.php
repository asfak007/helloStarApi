<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInterestBlogCategory extends Model
{
    /** @use HasFactory<\Database\Factories\UserInterestBlogCategoryFactory> */
    use HasFactory;

    protected $fillable = ['user_id','blog_category_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function blogCategory() {
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }
}
