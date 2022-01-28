<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Framework extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s A',
        'updated_at' => 'datetime:Y-m-d H:i:s A',
    ];

    public function becomeSeller()
    {
        return $this->belongsToMany(BecomeSeller::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }
}
