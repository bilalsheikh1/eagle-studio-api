<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json(ProductCategory::query()->get());
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
        try {
            $request->validate([
                'name' => ['required', 'string', 'unique:product_categories,name']
            ]);
            $product_category = ProductCategory::query()->create($request->all());
            return response()->json("Product category has been created.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ProductCategory $productCategory): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', Rule::unique('product_categories', 'name')->ignoreModel($productCategory)]
            ]);
            $productCategory->update($request->all());
            $productCategory->save();
            return response()->json("Product category has been updated.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductCategory $productCategory): \Illuminate\Http\JsonResponse
    {
        try {
            $productCategory->delete();
            return response()->json("Product template has been deleted.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
