<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'user_id');
    }

    public function becomeSeller()
    {
        return  $this->hasMany(BecomeSeller::class);
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function wishList()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

//    public function getIdAttribute($value)
//    {
//        return Crypt::encrypt($value);
//    }

}
