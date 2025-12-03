<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    /** @use HasFactory<\Database\Factories\SeoFactory> */
    use HasFactory;

    protected $fillable = [
        'page_name','meta_title','meta_description','meta_keywords','meta_image','canonical_url'
    ];
}
