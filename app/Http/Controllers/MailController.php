<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    public function OTPGenerator($length_of_string)
    {
        return substr(bin2hex(random_bytes($length_of_string)), 0, $length_of_string);
    }

    public function verifyEmail($email) {
        $data = array('name'=>"Muhammad Bilal");

        $password = $this->OTPGenerator(6);

        Mail::send(['text'=> "Password is {$password}"], $data, function($message) {
            $message->to('bilalsheikh923@gmail.com', 'Tutorials Point')->subject("Email Verification");
            $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "Basic Email Sent. Check your inbox.";
    }

    public function basic_email() {
        $data = array('name'=>"Virat Gandhi");

        Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
            $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "Basic Email Sent. Check your inbox.";
    }

    public function html_email() {
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
            $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "HTML Email Sent. Check your inbox.";
    }

    public function attachment_email() {
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
            $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
            $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
            $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "Email Sent with attachment. Check your inbox.";
    }

}
