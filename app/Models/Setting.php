<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    protected $fillable = [
        'header_title','title','description','keywords','address','phone','email','another_email',
        'logo','favicon','meta_image','facebook_link','instagram_link','twitter_link','linkedin_link','youtube_link','google_map_link'
    ];
}
