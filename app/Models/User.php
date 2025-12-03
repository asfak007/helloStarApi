<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name','email','number','image','role_id',
        'password','ref_token','point','is_varified'
    ];

    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function referralsGiven()
    {
        return $this->hasMany(Referral::class,'referrer_id');
    }

    public function referralsReceived()
    {
        return $this->hasMany(Referral::class,'referred_user_id');
    }

    public function rewards()
    {
        return $this->hasMany(UserReward::class);
    }

    public function pointsTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function serviceCategories()
    {
        return $this->hasMany(UserServiceCategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class,'customer_id');
    }

    public function providerReviews()
    {
        return $this->hasMany(Review::class,'provider_id');
    }

    public function providerEarnings()
    {
        return $this->hasMany(ProviderEarning::class,'provider_id');
    }

    public function payoutSettings()
    {
        return $this->hasOne(ProviderPayoutSetting::class,'provider_id');
    }

    public function payoutAccounts()
    {
        return $this->hasMany(ProviderPayoutAccount::class,'provider_id');
    }
}
