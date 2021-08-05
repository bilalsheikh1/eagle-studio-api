<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Auth::attempt($request->only('username', 'password'))) {
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

