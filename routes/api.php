<?php

use App\Http\Controllers\FeaturedImageController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FrameworkController;
use App\Http\Controllers\HandleFileController;
use App\Http\Controllers\OperatingSystemController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSubcategoryController;
use App\Http\Controllers\ProductTemplateController;
use App\Http\Controllers\ScreenshotController;
use App\Http\Controllers\ThumbnailImageController;
use App\Http\Controllers\CartController;
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

Route::post('/template-product', [ProductController::class, 'getProductByTemplate']);
Route::post('/sub-category-product', [ProductController::class, 'getProductBySubCategory']);
Route::post(`getFilteredData`, [ProductController::class,'getFilteredData']);

Route::post('/getFilterProduct',[ProductController::class,'getFilterProduct']);

// CART

Route::resource('cart',CartController::class);

Route::get('/{product}/featuredImage', [FeaturedImageController::class, 'index']);
Route::post('/{product}/featuredImage', [FeaturedImageController::class, 'upload']);
Route::delete('/{product}/featuredImage/{featuredImage}', [FeaturedImageController::class, 'destroy']);

Route::get('/{product}/thumbnailImage', [ThumbnailImageController::class, 'index']);
Route::post('/{product}/thumbnailImage', [ThumbnailImageController::class, 'upload']);
Route::delete('/{product}/thumbnailImage/{thumbnailImage}', [ThumbnailImageController::class, 'destroy']);

Route::get('/{product}/screenshot', [ScreenshotController::class, 'index']);
Route::post('/{product}/screenshot', [ScreenshotController::class, 'upload']);
Route::delete('/{product}/screenshot/{screenshot}', [ScreenshotController::class, 'destroy']);

Route::get('/{product}/file', [FileController::class, 'index']);
Route::post('/{product}/file', [FileController::class, 'upload']);
Route::delete('/{product}/file/{file}', [FileController::class, 'destroy']);

