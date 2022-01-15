<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Paypal;
use App\Models\Purchase;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $paypal = new Paypal();
        $order = new Order();
        $purchase = new Purchase();

        $order->status = true;
        $order->total = "200";
        $order->user()->associate(3);
        $order->save();
        $order->products()->attach([5,6]);

        $purchase->type = "paypal";
        $purchase->total = "200";
        $purchase->user()->associate(3);
        $purchase->save();
        $purchase->product()->attach([5,6]);

        $paypal->paypal_id = "12323wqw4sasd";
        $paypal->intent = "CAPTURE";
        $paypal->country_code = "US";
        $paypal->payer_name = "BILAL";
        $paypal->payer_surname = "SHAIKH";
        $paypal->payer_email = "bilal@gmail.com";
        $paypal->payer_id = "bilal123456";
        $paypal->currency_code = "USD";
        $paypal->amount = "200";
        $paypal->payer_email = "hassan@paypal.com";
        $paypal->payee_merchant_id = "erty8e239";
        $paypal->paypal_payment_status = "PENDING";
        $paypal->status = "COMPLETED";
        $paypal->payee_email = "hassan@gmail.com";
        $paypal->purchase_id = $purchase->id;
        $paypal->order_id = $order->id;
        $paypal->save();
        dd($paypal);
    }
}
