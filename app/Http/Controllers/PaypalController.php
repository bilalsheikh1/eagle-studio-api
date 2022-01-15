<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Paypal;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaypalController extends Controller
{

    use \App\Http\Traits\ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            "cart" => ["required"],
            "product_ids" => ["required", "array"],
            "paypalData" => ["required"]
        ]);
        try {

            $paypal = new Paypal();
            $order = new Order();
            $purchase = new Purchase();

            $order->status = true;
            $order->total = $request->cart["price"];
            $order->user()->associate($request->user()->id);
            $order->save();

            $order->products()->attach($request->product_ids);

            $purchase->type = "paypal";
            $purchase->total = $request->cart["price"];
            $purchase->user()->associate($request->user()->id);
            $purchase->save();
            $purchase->product()->attach($request->product_ids);

            $paypal->paypal_id = $request->paypalData["paypal_id"];
            $paypal->intent = $request->paypalData["intent"];
            $paypal->country_code = $request->paypalData["country_code"];
            $paypal->payer_name = $request->paypalData["payer_name"];
            $paypal->payer_surname = $request->paypalData["payer_surname"];
            $paypal->payer_email = $request->paypalData["payer_email"];
            $paypal->payer_id = $request->paypalData["payer_id"];
            $paypal->currency_code = $request->paypalData["currency_code"];
            $paypal->amount = $request->paypalData["amount"];
            $paypal->payee_email = $request->paypalData["payee_email"];
            $paypal->payee_merchant_id = $request->paypalData["payee_merchant_id"];
//            $paypal->paypal_payment_status = $request->paypal_payment_status;
            $paypal->status = $request->paypalData["status"];

            $paypal->payee_email = $request->payee_email;
            $paypal->purchase_id = $purchase->id;
            $paypal->order_id = $order->id;

            $paypal->save();

            Cart::query()->where("id", $request->cart->id)->update(["active" => "0"]);

            return $this->apiSuccess("Payment successful", []);;
        } catch (\Exception $exception) {
            return $this->apiFailed($exception->getMessage(), []);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paypal  $paypal
     * @return \Illuminate\Http\Response
     */
    public function show(Paypal $paypal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paypal  $paypal
     * @return \Illuminate\Http\Response
     */
    public function edit(Paypal $paypal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paypal  $paypal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paypal $paypal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paypal  $paypal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paypal $paypal)
    {
        //
    }
}
