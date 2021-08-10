<?php

use App\Http\Controllers\FrameworkController;
use App\Http\Controllers\OperatingSystemController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSubcategoryController;
use App\Http\Controllers\ProductTemplateController;
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
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::resource('product_template', ProductTemplateController::class);
Route::resource('product_category', ProductCategoryController::class);
Route::resource('framework', FrameworkController::class);
Route::resource('product', ProductController::class);
Route::resource('/{productCategory}/operating_system', OperatingSystemController::class);
Route::resource('/{productTemplate}/product_subcategory', ProductSubcategoryController::class);

