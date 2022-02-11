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
       $data= [["id" => "1", "type" => "single_app"], ["id" => "12", "type" => "multi_app"]];
       foreach ($data as $value)
       {
           $temp[] = [$value["id"] => ["type"=> $value["type"]]];
       }
        dd($temp);
    }
}
