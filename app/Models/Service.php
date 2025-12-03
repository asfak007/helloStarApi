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
        'amount','platform_fee','partial_payment','partial_payment_percentage','provider_percentage','reason','reason_image'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function areas() {
        return $this->hasMany(ServiceArea::class);
    }

    public function processes() {
        return $this->hasMany(ServiceProcess::class)->orderBy('serial_number','asc');
    }

    public function requirements() {
        return $this->hasMany(ServiceRequirement::class)->with('options');
    }

    public function faqs() {
        return $this->hasMany(ServiceFaq::class)->orderBy('serial_number', 'asc');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function getImageUrlAttribute()
    {

        $imagePath = $this->attributes['image'] ?? null || $this->attributes['reason_image'] ?? null  ;


        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }


        return asset('assets/images/demo/default.png');
    }

    public function getReasonImageUrlAttribute()
    {

        $imagePath =  $this->attributes['reason_image'] ?? null  ;


        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }


        return asset('assets/images/demo/default.png');
    }


    public function getFormattedAmountAttribute()
    {
        return '৳' . number_format($this->amount, 2);
    }

    public function getProviderAmountAttribute()
    {
        $percentage = $this->provider_percentage ?? 100;
        return ($this->amount * $percentage) / 100;
    }

    public function getTotalBookingsAttribute()
    {
        $count = $this->orders()->count();

        if ($count < 1000) {
            return (string) $count;
        }

        if ($count < 1000000) {
            return number_format($count / 1000, 1) . 'k'; // 1.2k, 3.3k
        }

        return number_format($count / 1000000, 1) . 'M'; // 1.2M
    }

    public function getRatingAttribute()
    {
        $ratings = $this->reviews()->pluck('rating'); // rating: 1–5 number

        if ($ratings->count() === 0) {
            return 0; // no rating yet
        }

        return round($ratings->avg(), 1); // format: 4.3
    }
}
