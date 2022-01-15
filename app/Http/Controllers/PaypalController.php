<?php

namespace App\Http\Controllers;

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


            $product_ids = [];

            $order->status = true;
            $order->total = $request->price;
            $order->user()->associate($request->user()->id);
            $order->save();

            $order->products()->attach($request->product_ids);

            $purchase->type = "paypal";
            $purchase->total = "200";
            $purchase->user()->associate(3);
            $purchase->save();
            $purchase->product()->attach($request->product_ids);

            $paypal->paypal_id = $request->paypal_id;
            $paypal->intent = $request->intent;
            $paypal->country_code = $request->country_code;
            $paypal->payer_name = $request->payer_name;
            $paypal->payer_surname = $request->payer_surname;
            $paypal->payer_email = $request->payer_email;
            $paypal->payer_id = $request->payer_id;
            $paypal->currency_code = $request->currency_code;
            $paypal->amount = $request->amount;
            $paypal->payer_email = $request->payer_email;
            $paypal->payee_merchant_id = $request->payee_merchant_id;
//            $paypal->paypal_payment_status = $request->paypal_payment_status;
            $paypal->status = $request->status;

            $paypal->payee_email = $request->payee_email;
            $paypal->purchase_id = $purchase->id;
            $paypal->order_id = $order->id;

            $paypal->save();

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
