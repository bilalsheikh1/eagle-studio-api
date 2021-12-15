<?php

namespace App\Models;

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

    public function operatingSystem()
    {
        return $this->belongsToMany(OperatingSystem::class);
    }

    public function framework()
    {
        return $this->belongsToMany(Framework::class);
    }
}
