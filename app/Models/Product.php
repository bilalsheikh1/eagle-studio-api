<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'features'];

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

    public function featuredImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(FeaturedImage::class);
    }

    public function thumbnailImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ThumbnailImage::class);
    }

    public function file(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(File::class);
    }
}
