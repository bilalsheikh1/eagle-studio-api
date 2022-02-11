<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $appends = [
        'order_id'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('type');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getOrderIdAttribute()
    {
        return str_pad($this->id, 10, '0', STR_PAD_LEFT);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

}
