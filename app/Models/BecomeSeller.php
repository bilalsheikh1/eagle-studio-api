<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BecomeSeller extends Model
{
    use HasFactory;
    protected $fillable = ['VAT_number', 'billing_address', 'billing_city','billing_zip_postal_code','company_name','developer_type','development_experience','paypal_email'];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

    public function productCategories()
    {
        return $this->belongsToMany(ProductCategory::class);
    }

    public function framework()
    {
        return $this->belongsToMany(Framework::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }
}
