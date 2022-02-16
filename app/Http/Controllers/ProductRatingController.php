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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductRating  $productRating
     * @return \Illuminate\Http\Response
     */
    public function show(ProductRating $productRating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductRating  $productRating
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductRating $productRating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductRating  $productRating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductRating $productRating)
    {
        //
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
