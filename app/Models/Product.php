<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function imagesVideos()
    {
        return $this->hasMany(ImagesVideo::class);
    }

    public function operatingSystem()
    {
        return $this->belongsToMany(OperatingSystem::class);
    }

    public function frameWork()
    {
        return $this->belongsTo(Framework::class);
    }

    public function productPlatform()
    {
        return $this->belongsTo(ProductPlatform::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(WebUser::class);
    }
}
