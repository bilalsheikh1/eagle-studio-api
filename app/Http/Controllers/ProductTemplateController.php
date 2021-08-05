<?php

namespace App\Http\Controllers;

use App\Models\ProductTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json(ProductTemplate::query()->get());
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
                'name' => ['required', 'string', 'unique:product_templates,name']
            ]);

            $product_template = ProductTemplate::query()->create($request->all());
            return response()->json("Product template has been created.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductTemplate  $productTemplate
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ProductTemplate $productTemplate): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', Rule::unique('product_templates', 'name')->ignoreModel($productTemplate)]
            ]);
            $productTemplate->update($request->all());
            $productTemplate->save();
            return response()->json("Product template has been updated.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductTemplate  $productTemplate
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductTemplate $productTemplate): \Illuminate\Http\JsonResponse
    {
        try {
            $productTemplate->delete();
            return response()->json("Product template has been deleted.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
