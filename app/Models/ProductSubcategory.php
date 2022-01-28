<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubcategory extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s A',
        'updated_at' => 'datetime:Y-m-d H:i:s A',
    ];

    public function productTemplate(): \Illuminate\Database\Eloquent\Relations\belongsToMany
    {
        return $this->belongsToMany(ProductTemplate::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }
}
