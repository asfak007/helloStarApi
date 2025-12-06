<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    /** @use HasFactory<\Database\Factories\OtpFactory> */
    use HasFactory;
    protected $fillable = ['phone','email', 'otp_code', 'is_used', 'expires_at'];

}
