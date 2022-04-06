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
use \App\Http\Controllers\SliderController;

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
Route::resource('product', ProductController::class)->except(['store', 'update', 'destroy']);
Route::get('get-template/product', [ProductController::class, 'index']);
//
Route::post('/template-product', [ProductController::class, 'getProductByTemplate']);
//PRODUCT SIUB
Route::post('/getFilterProduct',[ProductController::class,'getFilterProduct']);
Route::post('/product/get-filtered-products', [ProductController::class, 'getFilteredProducts']);
Route::post('/get-front-settings', [\App\Http\Controllers\SystemSettingController::class,'getFrontSettings']);
Route::get('get-slider', [sliderController::class, 'index']);

//GET PRIVACY POLICY
Route::get('privacyPolicy', [\App\Http\Controllers\PrivacyPolicyContorller::class,'index']);

//GET TERMS CONDITIONS
Route::get("termsCondition", [\App\Http\Controllers\TermsConditionController::class, "index"]);

//GET SPECFIC TERMS
Route::post("/terms-condition", [\App\Http\Controllers\TermsConditionController::class, "getSpecificTerm"]);
Route::get('/{product}/get-comment', [CommentController::class, "index"]);

//SELL YOUR APP
Route::get("sellYourApp", [\App\Http\Controllers\SellYourAppController::class,"index"]);

//Get Product By Title
Route::post('/getProductByTitle', [ProductController::class, 'getProductByTitle']);
Route::get('sub-category', [ProductSubcategoryController::class, 'getSubcategories']);
Route::get('user-detail/{user}', [UserController::class, "show"]);

Route::post("forgetPassword", [AuthController::class,"forgetPassword"]);

Route::get("/license", [\App\Http\Controllers\LicenseController::class,"index"]);


Route::middleware('auth:sanctum')->group(function () {

    Route::resource('system-settings', \App\Http\Controllers\SystemSettingController::class);
    Route::resource('product', ProductController::class)->only(['store', 'update', 'destroy']);
    Route::resource('slider', sliderController::class);
    Route::resource('user', UserController::class);
    Route::resource('/{productCategory}/operating_system', OperatingSystemController::class);
    Route::resource('/{productTemplate}/product_subcategory',ProductSubcategoryController::class);
    Route::resource('become-seller', \App\Http\Controllers\BecomeSellerController::class);
    Route::resource("wishlist", \App\Http\Controllers\WishlistController::class);

    //RATING ROUTE
    Route::resource("{product}/productRating",\App\Http\Controllers\ProductRatingController::class);

    //RATING MESSGAE
    Route::post("{product}/product-rating-message",[\App\Http\Controllers\ProductRatingController::class, "addRatedComment"]);

    //EARNING ROUTE
    Route::get("/get-earnings",[ProductController::class, "getProductsViews"]);

    //GET LAST 5 PRODUCTS
    Route::post("/last-5-product", [ProductController::class, "getLast5Products"]);

    //GET COMMENT
    Route::get("/fetch-messages", [CommentController::class, "fetchCommandsByUser"]);

    //GET PRODUCTS BY STATUS
    Route::post("get-products-status", [ProductController::class, "getProductsByStatus"]);

    //DOWNLOAD PRODUCT BY PRODUCT ID
    Route::post("product-download/{product}",[FileController::class, "downloadFileByProductID"]);

    Route::post('get/approved-products/{product}',[ProductController::class,'approvedProduct']);
    Route::post('get-product/{product}',[ProductController::class,'getProduct']);
    Route::post('add-product', [ProductController::class,'store']);

    Route::get('get-become-seller',[\App\Http\Controllers\BecomeSellerController::class, 'getBecomeSeller']);
    Route::get('getUser/{id}', [UserController::class, 'getUser']);
    Route::post('change-password/{user}', [UserController::class, 'changePassword']);

    Route::post('/sub-category-product', [ProductController::class, 'getProductBySubCategory']);
    Route::post('/getFilteredData', [ProductController::class,'getFilteredData']);

    // GET PRODUCT REQUEST
    Route::get('/product-request/{status}',[ProductController::class, 'getRequests']);

    //FILTERED PRODUCT REQUEST
    Route::post('product-fitlered/{status}', [ProductController::class, 'filteredProductRequest']);

    // CART
    Route::resource('cart',CartController::class);
//    Route::post('user/{user}/cart',[CartController::class, 'show']);

    //Comments Session
    Route::resource('/{product}/comment', CommentController::class);
    Route::get('/{product}/get-comment-product', [CommentController::class,"getCommentByProductID"]);
    Route::post('/{product}/reply-comment',[CommentController::class, 'commentReply']);

    //GET USER PROFILE
    Route::post('/user-profile', [\App\Http\Controllers\UserController::class,'getProfile']);
    Route::post('/get-users', [UserController::class,'getUsers']);
    Route::post('/get-filtered-users', [UserController::class,'getFilteredUsers']);

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

    //PAYMENT

    Route::resource('paypal', \App\Http\Controllers\PaypalController::class);

    //Orders
    Route::resource("order", \App\Http\Controllers\OrderController::class);
    Route::resource("purchase", \App\Http\Controllers\PurchaseController::class);

    //UPDATE PROFILE IMAGE
    Route::post("{id}/upload-profile-image", [UserController::class,'uploadProfileImage']);

});

    //ADMIN LOGIN
    Route::post('admin/login', [AuthController::class, 'adminLogin']);

Route::middleware('auth:sanctum')->prefix('admin')->name('admin.')->group(function () {

    //RESOURCES
    Route::resource('system-settings', \App\Http\Controllers\SystemSettingController::class);
    Route::resource('product', ProductController::class);
    Route::resource('slider', sliderController::class);
    Route::resource('user', UserController::class);
    Route::resource('/{productCategory}/operating_system', OperatingSystemController::class);
    Route::resource('/{productTemplate}/product_subcategory',ProductSubcategoryController::class);
    Route::resource('framework', FrameworkController::class);
    Route::resource('product_category', ProductCategoryController::class);
    Route::resource('product_template', ProductTemplateController::class);
    Route::resource("order", \App\Http\Controllers\OrderController::class);
    Route::resource("purchase", \App\Http\Controllers\PurchaseController::class);
    Route::resource("privacyPolicy", \App\Http\Controllers\PrivacyPolicyContorller::class);
    Route::resource("termsCondition", \App\Http\Controllers\TermsConditionController::class);
    Route::resource("sellYourApp", \App\Http\Controllers\SellYourAppController::class);

    Route::get("/license", [\App\Http\Controllers\LicenseController::class,"index"]);
    Route::put("/license", [\App\Http\Controllers\LicenseController::class,"update"]);

    //EAMIL DESIGN
    Route::resource("/email-design", \App\Http\Controllers\EmailDesignController::class);

    //GET EMAIL DESIGN BY KEY
    Route::post("/get-email-by-key/{key}", [\App\Http\Controllers\EmailDesignController::class, "getEmailFiledByKey"]);

    Route::put("/terms-update", [\App\Http\Controllers\TermsConditionController::class, "update"]);

    //FILTERED ORDERS
    Route::post("/filter-order", [\App\Http\Controllers\OrderController::class, "getFilteredOrders"]);

    //PURCHASE DATA
    Route::get("/get-purchase-data", [\App\Http\Controllers\PurchaseController::class, "getAllPurchaseData"]);

    //DASHBOARD DATA
    Route::post("/dashboard", [\App\Http\Controllers\DashbaordController::class, "index"]);

    //GET PRODUCT BY USER-ID
    Route::post("get-user-products/{user}", [UserController::class,"getProductsByUser"]);

    //GET USER DETAILS
    Route::post("user-details/{id}", [UserController::class,'getUserDetails']);

    //UPDATE USER STATUS
    Route::post('/update-user-status/{user}/{status}', [UserController::class, 'updateStatus']);

    //GET USERS
    Route::post('/get-users', [UserController::class,'getUsers']);

    //REQUESTS ROUTES
    Route::post('get/approved-products/{product}',[ProductController::class,'approvedProduct']);

    //REQUEST PRODUCTS
    Route::get('/product-request/{status}',[ProductController::class, 'getRequests']);

    //GET ORDERS BY USER
    Route::post('/get-order-by-user/{user}',[\App\Http\Controllers\OrderController::class, 'getOrderByUser']);

    //GET ORDERS
    Route::get("/get-orders",[\App\Http\Controllers\OrderController::class, "getOrders"]);

    //GET PRODUCT BY ID
    Route::post('get-product/{product}',[ProductController::class,'getProduct']);

    //GET
    Route::post('/{product}/file', [FileController::class, 'downloadFile']);

    //UPDATE REQUEST
    Route::put('{product}/product-request/{status}', [ProductController::class, 'mutateRequest']);

    //LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

});
