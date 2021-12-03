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
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\CommentController;
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
Route::post('/sign-up', [AuthController::class, 'signUp']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::resource('product_template', ProductTemplateController::class);
Route::resource('product_category', ProductCategoryController::class);
Route::resource('framework', FrameworkController::class);
Route::resource('product', ProductController::class)->only(['get', 'index','getProductBySubCategory','getFilterProduct','getProductByTemplate','filteredProductRequest','getProductByTitle','show','getFilteredData']);
Route::post('/template-product', [ProductController::class, 'getProductByTemplate']);
Route::post('/getFilterProduct',[ProductController::class,'getFilterProduct']);
Route::post('/product/get-filtered-products', [ProductController::class, 'getFilteredProducts']);

Route::middleware('auth:sanctum')->group(function () {

    //Route::resource('product_template', ProductTemplateController::class);
    //Route::resource('product_category', ProductCategoryController::class);
    //Route::resource('framework', FrameworkController::class);
    //Route::resource('product', ProductController::class);
    Route::resource('product', ProductController::class);
    Route::post('add-product', [ProductController::class,'store']);
    Route::resource('user', UserController::class);
    Route::resource('/{productCategory}/operating_system', OperatingSystemController::class);
    Route::resource('/{productTemplate}/product_subcategory',ProductSubcategoryController::class);


    Route::get('sub-category', [ProductSubcategoryController::class, 'getSubcategories']);

    //Route::post('/template-product', [ProductController::class, 'getProductByTemplate']);
    Route::post('/sub-category-product', [ProductController::class, 'getProductBySubCategory']);
    Route::post('/getFilteredData', [ProductController::class,'getFilteredData']);

    //Route::post('/getFilterProduct',[ProductController::class,'getFilterProduct']);

    // GET PRODUCT REQUEST
    Route::get('/product-request/{status}',[ProductController::class, 'getRequests']);

    //UPDATE REQUEST
    Route::put('{product}/product-request/{status}', [ProductController::class, 'mutateRequest']);

    //FILTERED PRODUCT REQUEST
    Route::post('product-fitlered/{status}', [ProductController::class, 'filteredProductRequest']);

    //Get Product By Title
    Route::post('/getProductByTitle', [ProductController::class, 'getProductByTitle']);

    // CART
    Route::resource('cart',CartController::class);
//    Route::post('user/{user}/cart',[CartController::class, 'show']);

    //Comments Session
    Route::resource('/{product}/comment', CommentController::class);
    Route::post('/{product}/reply-comment',[CommentController::class, 'commentReply']);

    //GET USER PROFILE
    Route::post('/user-profile', [\App\Http\Controllers\UserController::class,'getProfile']);
    Route::post('/get-users', [UserController::class,'getUsers']);
    Route::post('/get-filtered-users', [UserController::class,'getFilteredUsers']);
    Route::post('/update-user-status/{user}/{status}', [UserController::class, 'updateStatus']);

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

});
