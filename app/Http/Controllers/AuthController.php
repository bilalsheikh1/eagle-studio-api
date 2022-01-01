<?php

namespace App\Http\Controllers;

use App\Models\BecomeSeller;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'username' => ['string', 'min:3'],
            'password' => ['string', 'min:3']
        ]);
        try {
            if (!Auth::attempt(['username' => $request->username, 'password' => $request->password, 'is_admin' => 0]))
                return response()->json('Invalid login details', 500);
            $token = $request->user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'id' => Crypt::encrypt($request->user()->id),
                'user' => $request->user(),
                'becomeSeller' => BecomeSeller::query()->where('user_id', $request->user()->id)->exists(),
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function signUp(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => ['string', 'min:3', 'required'],
            'username' => ['string', 'min:3', 'required', 'unique:users,username'],
            'email' => ['unique:users,email', 'email', 'required'],
            'password' => ['string', 'min:4', 'required']
        ]);
        try{
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            if (Auth::attempt($request->only('username', 'password'))) {
                $token = $request->user()->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'id' => Crypt::encrypt($request->user()->id),
                    'user' => $request->user()
                ]);
            } else return response()->json('user not created', 500);
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
}

