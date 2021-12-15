<?php

namespace App\Http\Controllers;

use App\Models\OperatingSystem;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OperatingSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProductCategory $productCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ProductCategory $productCategory): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($productCategory->operatingSystems()->get());
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductCategory $productCategory
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductCategory $productCategory, Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:operating_systems,name']
        ]);
        try {
            $operating_system = $productCategory->operatingSystems()->create($request->all());
            return response()->json("Operating system has been created.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductCategory $productCategory
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\OperatingSystem $operatingSystem
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductCategory $productCategory, Request $request, OperatingSystem $operatingSystem): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', Rule::unique('operating_systems', 'name')->ignoreModel($operatingSystem)]
            ]);
            $operating_system = $operatingSystem->update($request->all());
            $operatingSystem->save();
            return response()->json("Operating system has been created.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductCategory $productCategory
     * @param \App\Models\OperatingSystem $operatingSystem
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductCategory $productCategory, OperatingSystem $operatingSystem): \Illuminate\Http\JsonResponse
    {
        try {
            $operatingSystem->delete();
            return response()->json("Operating system has been deleted.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
