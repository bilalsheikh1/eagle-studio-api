<?php

namespace App\Http\Controllers;

use App\Mail\ConformationMail;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\ProductTemplate;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function test(Product $product)
    {
//        $product = Product::query()->with(['productTemplate', 'thumbnailImage', "productCategory",
//            "productRating" => function($q){
////                $q->select("rating", DB::raw("count(rating) as count"))->groupBy("rating")->get();
//
//            }])->where("status", "1")->get();

        $product = Product::query()->with(['productTemplate', 'thumbnailImage', "productCategory"])->withAvg("productRating","rating")->
        where("status", "1")->paginate(10);

//        foreach ($product as $index => $value)
//        {
//            $product[$index]->productRating = $value->productRating;
//        }
        dd($product->toArray());
    }
}
