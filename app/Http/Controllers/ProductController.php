<?php

namespace App\Http\Controllers;

use App\Models\FeaturedImage;
use App\Models\File;
use App\Models\Product;
use App\Models\Screenshot;
use App\Models\ThumbnailImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json(Product::query()->with(['productTemplate', 'framework', 'productCategory', 'productSubcategory', 'operatingSystems'])->get());
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'unique:products,title'],
            'product_template' => ['required', 'exists:product_templates,id'],
            'product_category' => ['required', 'exists:product_categories,id'],
            'product_subcategory' => ['required', 'exists:product_subcategories,id'],
            'operating_systems.*' => ['required', 'exists:operating_systems,id'],
            'framework' => ['required', 'exists:frameworks,id'],
            'description' => ['required'],
/*            'features' => ['required'],
            'featured_image' => ['required', 'dimensions:width=650,height=290'],
            'thumbnail_image' => ['required', 'dimensions:width=200,height=140'],
            'screenshots.*' => ['required', 'image'],
            'youtube_link' => ['nullable', 'URL'],
            'google_play_link' => ['nullable', 'URL'],
            'app_store_link' => ['nullable', 'URL'],
            'files.*' => ['required', 'mimes:zip,rar'],
            'single_app_license' => ['required'],
            'multi_app_license' => ['required'],
            'development_hours' => ['numeric']*/
        ]);

        try {
            $product = new Product;
            $product->fill($request->all());
            $product->productTemplate()->associate($request->template_type);
            $product->framework()->associate($request->framework);
            $product->productCategory()->associate($request->product_category);
            $product->productSubcategory()->associate($request->product_subcategory);
            $product->save();
            $product->operatingSystems()->sync($request->operating_systems);
            return response()->json($product);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($product->load(['productTemplate', 'productCategory', 'productSubcategory', 'operatingSystems', 'framework', 'featuredImage', 'screenshots', 'thumbnailImage', 'file']));
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        if($request->step3) {
            $request->validate([
                'single_app_license' => ['required'],
                'multi_app_license' => ['required'],
                'development_hours' => ['required', 'numeric'],
            ]);
        } else {
            $request->validate([
                'youtube_link' => ['nullable', 'URL'],
                'google_play_link' => ['nullable', 'URL'],
                'app_store_link' => ['nullable', 'URL'],
            ]);
        }

        try {
            if($request->step3) {
                $product->single_app_license = $request->single_app_license;
                $product->multi_app_license = $request->multi_app_license;
                $product->development_hours = $request->app_store_link;
            } else {
                $product->youtube_link = $request->youtube_link;
                $product->google_play_link = $request->google_play_link;
                $product->app_store_link = $request->app_store_link;
            }
            $product->save();
            return response()->json($product);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product): \Illuminate\Http\JsonResponse
    {
        try {
            $product->delete();
            return response()->json("Product has been deleted.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
