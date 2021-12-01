<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return response()->json(Cart::query()->with(['product', 'product.thumbnailImage'])->where('user_id',$id)->get());
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
            'price' => ['string', 'required'],
        ]);
        try{
            $data = explode("-",$request->price);
            $cart = new Cart();
            $cart->type = $data[0];
            $cart->price = $data[1];
            $cart->product()->associate($data[2]);
            $cart->user()->associate(1);
            $cart->save();
            return response()->json($cart->with(['product', 'product.thumbnailImage'])->get());
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
//     * @param  \App\Models\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Cart::query()->with(['product', 'product.thumbnailImage'])->where('user_id',$id)->get();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        try{
            $cart->delete();
            return response()->json($cart->with(['product', 'product.thumbnailImage'])->get());
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }
}
