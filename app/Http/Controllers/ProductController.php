<?php

namespace App\Http\Controllers;

use App\Models\FeaturedImage;
use App\Models\File;
use App\Models\Product;
use App\Models\Screenshot;
use App\Models\ThumbnailImage;
use Illuminate\Http\Request;
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
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'unique:products,title'],
            'template_type' => ['required', 'exists:product_templates,id'],
            'product_category' => ['required', 'exists:product_categories,id'],
            'product_subcategory' => ['required', 'exists:product_subcategories,id'],
            'operating_systems.*' => ['required', 'exists:operating_systems,id'],
            'framework' => ['required', 'exists:frameworks,id'],
            'description' => ['required'],
            'features' => ['required'],
            'featured_image' => ['required'],
            'thumbnail_image' => ['required'],
            'screenshots.*' => ['required'],
            'youtube_link' => ['nullable', 'URL'],
            'google_play_link' => ['nullable', 'URL'],
            'app_store_link' => ['nullable', 'URL'],
            'files.*' => ['required'],
            'single_app_license' => ['required'],
            'multi_app_license' => ['required'],
            'development_hours' => ['numeric']

        ]);

        try {
            $product = new Product;
            $product->fill($request->all());
            $product->productTemplate()->associate($request->template_type);
            $product->framework()->associate($request->framework);
            $product->productCategory()->associate($request->product_category);
            $product->productSubcategory()->associate($request->product_subcategory);
            $featureImagePath = $request->file('featured_image')->store('featured_images');
            $thumbnailImagePath = $request->file('thumbnail_image')->store('thumbnail_images');
            $filePath = $request->file('file')->store('files');
            $product->featured_image = $featureImagePath;
            $product->thumbnail_image = $thumbnailImagePath;
            $product->file = $filePath;
            $product->save();
            $operatingSystemArray = explode(",", $request->operating_systems);
            $product->operatingSystems()->sync($operatingSystemArray);
            foreach ($request->screenshots as $screenshot) {
                $scPath = $screenshot->store('screenshots');
                $screenshot = new Screenshot;
                $screenshot->path = $scPath;
                $screenshot->name = $scPath;
                $product->screenshots()->save($screenshot);
            }
            return response()->json("Product has been created.");
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
            return response()->json($product->load('operatingSystems'));
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
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
