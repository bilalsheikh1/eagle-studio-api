<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Paypal;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(Request $request)
    {
//        ->whereRelation("products","user_id","=","3")
        $orders = Order::query()->with("products")->get();
//        $orders = Product::query()->with("orders")->get();
//        $orders = Product::query()->with("orders")->get();
        dd($orders);
    }
}
