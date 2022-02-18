<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;

class ProductRatingController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product, Request $request)
    {
        try {
            $productRating = ProductRating::query()->where("user_id", $request->user()->id)->where("product_id", $product->id)->first();
            return $this->apiSuccess("", $productRating);
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product, Request $request)
    {
        $request->validate([
           "rating" => ["required"]
        ]);
        try {
            $productRating = ProductRating::query()->where("product_id", $request->id)->where("user_id", $request->user()->id)->first();
            if(!empty($productRating))
                return $this->apiSuccess("Already rating gived");
            $productRating = new ProductRating();
            $productRating->rating = $request->rating;
            $productRating->product()->associate($product);
            $productRating->user()->associate($request->user()->id);
            $productRating->save();
            return $this->apiSuccess("Thanks for giving rating for this product {$product->title}", $productRating);
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    public function getProductRating()
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductRating  $productRating
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductRating $productRating)
    {
        //
    }
}
