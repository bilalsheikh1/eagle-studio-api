<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $list = Wishlist::query()->with(["product.productCategory","product.thumbnailImage"])->where("user_id", $request->user()->id)->get();
            return $this->apiSuccess("",$list);
        } catch (\Exception $exception){
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
    public function store(Request $request)
    {
        $request->validate([
           "product_id" => ["required", "exists:products,id"]
        ]);
        try {
            $validate = Wishlist::query()->whereRelation("product", "product_id","=",$request->product_id)->where("user_id",$request->user()->id)->first();
            if(!empty($validate))
                return $this->apiSuccess("this product already exists in wishlist");
            $wishlist = new Wishlist();
            $wishlist->user()->associate($request->user()->id);
            $wishlist->save();
            $wishlist->product()->attach($request->product_id);
            return $this->apiSuccess("product has been added in wishlist");
        } catch (\Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show(Wishlist $wishlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist, Request $request)
    {
        try {
            $wishlist->product()->detach($request->id);
            $validate = Wishlist::query()->has("product")->where("user_id",$request->user()->id)->first();
            if(empty($validate))
                $wishlist->delete();
            $list = Wishlist::query()->with(["product.productCategory","product.thumbnailImage"])->where("user_id", $request->user()->id)->get();
            return $this->apiSuccess("product has been removed", $list);
        } catch (\Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }
}
