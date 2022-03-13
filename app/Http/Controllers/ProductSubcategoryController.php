<?php

namespace App\Http\Controllers;

use App\Models\ProductSubcategory;
use App\Models\ProductTemplate;
use Illuminate\Http\Request;

class ProductSubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProductTemplate $productTemplate
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ProductTemplate $productTemplate): \Illuminate\Http\JsonResponse
    {
        try {
            if($productTemplate->urn == "ready-2-use")
            {
                return response()->json(ProductSubcategory::query()->get());
            }
            return response()->json($productTemplate->productSubcategories()->get());
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getSubcategories(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(ProductSubcategory::query()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductTemplate $productTemplate, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string']
            ]);
            $subcategory = $productTemplate->productSubcategories()->create($request->all());
            return response()->json("Product subcategory has been created.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductSubcategory  $productSubcategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductTemplate $productTemplate, Request $request, ProductSubcategory $productSubcategory): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string']
            ]);
            $productSubcategory->update($request->all());
            $productSubcategory->save();
            return response()->json("Product subcategory has been updated.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductSubcategory  $productSubcategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductTemplate $productTemplate, ProductSubcategory $productSubcategory): \Illuminate\Http\JsonResponse
    {
        try {
            $productSubcategory->delete();
            return response()->json("Product Subcategory has been deleted.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
