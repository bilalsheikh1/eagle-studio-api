<?php

namespace App\Http\Controllers;

use App\Models\Framework;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FrameworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json(Framework::query()->get());
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
                'name' => ['required', 'unique:frameworks,name']
            ]);
            $framework = Framework::query()->create($request->all());
            return response()->json("Framework has been created.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Framework  $framework
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Framework $framework): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', Rule::unique('frameworks', 'name')->ignoreModel($framework)]
            ]);
            $framework->update($request->all());
            $framework->save();
            return response()->json("Framework has been updated.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Framework  $framework
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Framework $framework): \Illuminate\Http\JsonResponse
    {
        try {
            $framework->delete();
            return response()->json("Framework has been deleted.");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
