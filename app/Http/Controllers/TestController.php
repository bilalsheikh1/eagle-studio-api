<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Paypal;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder;

class TestController extends Controller
{
    public function test(Request $request)
    {
       $purchase = new Purchase();
       $purchase->id = 11;
       $purchase->type = "ok";
        $purchase->total = "ok";
       $purchase->user()->associate(1);
       $purchase->save();
    }
}
