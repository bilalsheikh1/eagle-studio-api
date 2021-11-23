<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Srmklive\PayPal\Facades\PayPal;
use Srmklive\PayPal\Services\PayPal as PaypalClient;

class TestController extends Controller
{
    public function test(Request $request){
        $provider = new PaypalClient;
        $provider = PayPal::setProvider();
//        dd($provider);
        $provider->setApiCredentials([
            'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
                'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
                'app_id'            => 'APP-80W284485P519543T',
            ],
//            'live' => [
//                'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', ''),
//                'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
//                'app_id'            => '',
//            ],

            'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => env('PAYPAL_CURRENCY', 'USD'),
            'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
            'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
        ]);
        dd($provider->getAccessToken());
    }
}
