<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paypal extends Model
{
    use HasFactory;

   public function  purchase()
   {
       return $this->hasOne(Purchase::class);
   }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
