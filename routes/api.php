<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//
//    //Logout
//    Route::post('/logout', [AuthController::class, 'logout']);
//
//
////    return $request->user();
//});

//Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', function (){
        return auth()->user();
    });

    Route::get('/pending-user', [\App\Http\Controllers\WebUserController::class,'pendingUser']);
    Route::get('/active-user', [\App\Http\Controllers\WebUserController::class,'activeUser']);
    Route::get('/discard-user', [\App\Http\Controllers\WebUserController::class,'discardUser']);
    Route::patch('/update-status/{webUser}', [\App\Http\Controllers\WebUserController::class, 'updateStatus']);
    Route::resource("/framework", \App\Http\Controllers\FrameworkController::class);
//});

