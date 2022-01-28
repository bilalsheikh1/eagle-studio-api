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
//        $orders = Purchase::query()->join('product_purchase as pp', 'purchases.id', '=', 'pp.id')
//            ->join('products as p', 'p.id', '=', 'pp.product_id')
//            ->join("files as f", "f.id","=","p.id")
//            ->where("purchases.user_id", "=",3)
//            ->select(['p.title', "f.id as file_id", 'pp.*'])
//            ->get();

        $product = Product::query()->with("file")->where("id", 1)->first();
        dd($product->file);
    }
}
