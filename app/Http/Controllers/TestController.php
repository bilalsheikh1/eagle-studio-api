<?php

namespace App\Http\Controllers;

use App\Mail\ConformationMail;
use App\Mail\SendPasswordMail;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function OTPGenerator($length_of_string)
    {
        return substr(bin2hex(random_bytes($length_of_string)), 0, $length_of_string);
    }

    public function test(Product $product)
    {
        $data = array('name'=>"Muhammad Bilal");

        $password = $this->OTPGenerator(6);
//        Mail::send(['text'=> "Password is {$password}"], $data, function($message) {
//            $message->to('bilalsheikh923@gmail.com', 'Tutorials Point')->subject("Email Verification");
//        });
        Mail::to('bilalsheikh923@gmail.com')->send(new SendPasswordMail());

        echo "Basic Email Sent. Check your inbox.";
    }
}
