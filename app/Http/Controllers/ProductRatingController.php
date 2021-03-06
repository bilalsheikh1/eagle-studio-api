<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Database\Eloquent\Model;
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
            $productRating = ProductRating::query()->where("product_id", $product->id)->where("user_id", $request->user()->id)->first();
            if(!empty($productRating))
                return $this->apiSuccess("Already rated");
            $productRating = new ProductRating();
            $productRating->message = $request->message;
            $productRating->rating = $request->rating;
            $productRating->product()->associate($product);
            $productRating->user()->associate($request->user()->id);
            $productRating->save();
            return $this->apiSuccess("Thanks for giving rating for this product {$product->title}", $productRating);
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    public function update(Product $product, Request $request)
    {
        $request->validate([
            "message" => ["string", "required"]
        ]);
        try {
            $product->message = $request->message;
            $product->update();
            return $this->apiSuccess("Product {$product->title} rating has been added");
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }


    public function addRatedComment(Product $product, Request $request)
    {
        $request->validate([
            "message" => ["required"]
        ]);
        try {
            $productRating = ProductRating::query()->where("product_id", $product->id)->where("user_id", $request->user()->id)->first();
            if(!empty($productRating)) {
                if ($productRating->message != "" && $productRating->message)
                    return $this->apiSuccess("Already rating given");
                else {
                    $productRating->message = $request->message;
                    $productRating->update();
                    return $this->apiSuccess("Product Rating Comment has been added");
                }
            }
            return $this->apiSuccess("please first rate to star then comments");

//            $productRating = new ProductRating();
//            $productRating->message = $request->message;
//            $productRating->rating = $request->rating;
//            $productRating->product()->associate($product);
//            $productRating->user()->associate($request->user()->id);
//            $productRating->save();
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
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
