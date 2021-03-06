<?php

namespace App\Models;

use App\Http\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'features','youtube_link','google_play_link','app_store_link','single_app_license','multi_app_license','reskinned_app_license','development_hours'];

    public function productTemplate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductTemplate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function productSubcategory(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ProductSubcategory::class);
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

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Cart::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function purchase(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Purchase::class);
    }

    public function wishList()
    {
        return $this->belongsToMany(Wishlist::class);
    }

    public function productViews()
    {
        return $this->hasMany(ProductView::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

    public function productRating()
    {
        return $this->hasMany(ProductRating::class);
    }
}
