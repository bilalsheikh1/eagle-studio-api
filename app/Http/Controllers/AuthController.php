<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Mail\SendPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiResponse;

    public function OTPGenerator($length_of_string)
    {
        return substr(bin2hex(random_bytes($length_of_string)), 0, $length_of_string);
    }

    public function sendPasswordToMail($email,$password)
    {
        return Mail::to($email)->send(new SendPasswordMail($password));
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'username' => ['string', 'min:3'],
            'password' => ['string', 'min:3']
        ]);
        try {
            if (!Auth::attempt(['username' => $request->username, 'password' => $request->password, 'is_admin' => 0, "active" => 1]))
                return response()->json('Invalid login details', 500);
            $token = $request->user()->createToken('auth_token')->plainTextToken;
            $user = $request->user();
            $user->email_verified_at = date('Y-m-d h:i:s');
            $user->save();
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'id' => Crypt::encrypt($request->user()->id),
                'user' => $request->user(),
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function signUp(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => ['string', 'min:3', 'required'],
            'username' => ['string', 'min:3', 'required'],
            'email' => ['email', 'required']
//            'username' => ['string', 'min:3', 'required', 'unique:users,username'],
//            'email' => ['email', 'required', 'unique:users,email'],
//            'password' => ['string', 'min:4', 'required']
        ]);
        try{
            $passwordGenerate = $this->OTPGenerator(8);
            $user = User::query()->where("email", $request->email)->where("username", $request->username)->whereNull("email_verified_at")->first();
            if(!empty($user)) {
                $user->password = Hash::make($passwordGenerate);
                $user->save();
                $this->sendPasswordToMail($request->email, $passwordGenerate);
                return response()->json("Password has been send to {$request->email}");
            }
            $user = User::query()->where("email", $request->email)->where("username", $request->username)->whereNotNull("email_verified_at")->first();
            if(!empty($user))
                return response()->json("Email & Username have already been taken", 500);
            else
            {
                $request->validate([
                    'username' => ['string', 'min:3', 'required', 'unique:users,username'],
                    'email' => ['email', 'required', 'unique:users,email']
                ]);
                $user = new User();
                $user->name = $request->name;
                $user->username = $request->username;
                $user->email = $request->email;
                $user->password = Hash::make($passwordGenerate);
                $user->save();
                $this->sendPasswordToMail($request->email, $passwordGenerate);
                return response()->json("Password has been send to email");
            }
//            if (Auth::attempt($request->only('username', 'password'))) {
//                $token = $request->user()->createToken('auth_token')->plainTextToken;
//            } else return response()->json('user not created', 500);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function adminLogin(Request $request) :\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'username' => ['string', 'min:3'],
            'password' => ['string', 'min:3']
        ]);
        try {
            if (!Auth::attempt(['username' => $request->username, 'password' => $request->password, 'is_admin' => 1])) {
                return response()->json('Invalid login details', 401);
            }

            $token = $request->user()->createToken('auth_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $request->user()
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json("User logout successfully.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
           "email" => ["required", "exists:users,email"]
        ]);
        try {
            $user = User::query()->where("email",$request->email)->first();
            $password =  $this->OTPGenerator(8);
            $user->password =  Hash::make($password);
            $user->save();
            $this->sendPasswordToMail($request->email, $password);
            return $this->apiSuccess("Password has been send to provided email");
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }


}

