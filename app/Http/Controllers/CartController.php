<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CartController extends Controller
{

    use \App\Http\Traits\ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(Cart::query()->where("user_id",$request->user()->id)->where("active", "1")->get());
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
            $cart = Cart::query()->where("user_id",$request->user()->id)->where("active", "1")->first();

            if(empty($cart)) {

                $cart = new Cart();

                $cart->price = $data[1];
                $cart->user()->associate($request->user()->id);
                $cart->save();
                $cart->product()->attach($data[2], ["type" => $data[0]]);

                $arrayData = $cart->with(['product', 'product.thumbnailImage'])->where("active", "1")->get();
                $message = "Product added into cart";
                return $this->apiSuccess($message, $arrayData);
            }
            else {

                $validate = Cart::query()->with(["product" => function ($q) use ($cart, $data) {
                    $q->where("cart_id", $cart->id)->where("product_id", $data[2]);
                }])->where("active", "1")->first();

                if(count($validate->product) > 0) {
                    $message = "This product already selected in cart list please remove first then add";
                    $arrayData = $cart->with(['product', 'product.thumbnailImage'])->where("active", "1")->get();
                    return $this->apiSuccess($message, $arrayData,"",false);
                }
            }

            $cart->price = $cart->price + $data[1];
            $cart->save();
            $cart->product()->attach($data[2], ["type" => $data[0]]);

            $arrayData = $cart->with(['product', 'product.thumbnailImage'])->where("active", "1")->get();
            $message = "Product added into cart";
            return $this->apiSuccess($message, $arrayData,"",true);
        } catch (\Exception $exception) {
            return $this->apiFailed("", [],$exception->getMessage() ,false, 500);
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
        $data = Cart::query()->with(['product', 'product.thumbnailImage'])->where("active", "1")->where('user_id',Crypt::decrypt($id))->get();
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
    public function destroy(Cart $cart, Request $request)
    {
        $apiResponse = new \stdClass();
        try{
            $cart->product()->detach($request->product_id);

            $cart->price = $cart->price - $request->price;
            $cart->save();

            $validate = Cart::query()->with(["product" => function ($q) use ($cart, $request) {
                $q->where("cart_id", $cart->id);
            }])->where("active", "1")->first();

            if(count($validate->product) == 0) {
                $cart->delete();
            }
            $message = "product has been removed on cart";
            $arrayData = $cart->with(['product', 'product.thumbnailImage'])->where("active", "1")->get();
            return $this->apiSuccess($message, $arrayData,"",true);
        } catch (\Exception $exception) {
            return $this->apiFailed("", [],$exception->getMessage(), false);
        }
    }
}
