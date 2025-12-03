<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id','title','description','image','conditions',
        'amount','platform_fee','partial_payment','partial_payment_percentage','provider_percentage'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function areas() {
        return $this->hasMany(ServiceArea::class);
    }

    public function processes() {
        return $this->hasMany(ServiceProcess::class);
    }

    public function requirements() {
        return $this->hasMany(ServiceRequirement::class);
    }

    public function faqs() {
        return $this->hasMany(ServiceFaq::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
