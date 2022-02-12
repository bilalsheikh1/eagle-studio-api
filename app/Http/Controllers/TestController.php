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
        $purchase = Purchase::query()->where("id", 1)->first();
//        $purchase = $purchase->load("products");
        dd($purchase);
    }
}
