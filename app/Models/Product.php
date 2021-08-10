<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'features', 'youtube_link', 'google_play_link', 'app_store_link', 'single_app_license', 'multi_app_license', 'development_hours'];

    public function productTemplate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductTemplate::class);
    }

    public function productCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function productSubcategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductSubcategory::class);
    }

    public function framework(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Framework::class);
    }

    public function operatingSystems(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(OperatingSystem::class);
    }

    public function screenshots(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Screenshot::class);
    }
}
