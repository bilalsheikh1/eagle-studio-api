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
        $orders =Order::query()->join('order_product as op', 'orders.id', '=', 'op.id')
            ->join('products as p', 'p.id', '=', 'op.product_id')
            ->join('users as u', "u.id", '=', "orders.user_id")
            ->where('p.user_id', 3)
            ->select(['p.title','p.id as product_id', 'orders.*', 'u.name'])
            ->get();
        dd($orders->toArray());
    }
}
