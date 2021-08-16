<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScreenshotController extends Controller
{
    /**
     * Uploads image to server
     * @param Product $product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Product $product, Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => ['required']
        ]);
        try {
            $image = new Screenshot();
            $image->name = $request->file('file')->getClientOriginalName();
            $image->path = $request->file('file')->storeAs('screenshots', $image->name, 'public');
            $image->url = Storage::disk('public')->url($image->path);
            $product->screenshots()->save($image);
            return response()->json($image);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function destroy(Product $product, Screenshot $screenshot): \Illuminate\Http\JsonResponse
    {
        try {
            $res = Storage::disk('public')->delete($screenshot->path);
            if($res) {
                $screenshot->delete();
            }
            return response()->json($res);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
