<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    /** @use HasFactory<\Database\Factories\UserDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id','professional_category_id','education_type',
        'division_id','district_id','area','thana_id',
        'permanent_address','nid_front_side','nid_back_side','certificates','professional_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function thana()
    {
        return $this->belongsTo(Thana::class);
    }
}
