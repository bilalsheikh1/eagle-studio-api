<?php

namespace App\Http\Controllers;

use App\Mail\ConformationMail;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function test(Product $product)
    {
        $data = Purchase::query()->has("products")->with("products.productRating")->where("user_id", 4)->orderByDesc("id")->get();
        foreach ($data as $index => $v)
        {
            $data[$index]->products = $v->products;
            $data[$index]->prdouct_ratings = $v->productRating;
        }
        dd($data);
    }
}
