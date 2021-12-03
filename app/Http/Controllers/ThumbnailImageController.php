<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ThumbnailImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThumbnailImageController extends Controller
{

    public function index(Product $product, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($product->thumbnailImage);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Uploads image to server
     * @param Product $product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Product $product, Request $request): \Illuminate\Http\JsonResponse
    {
//        $request->validate([
//            'file' => ['required', 'dimensions:width=200,height=140']
//        ]);
        try {
            $product->thumbnailImage()->delete();
            $image = new ThumbnailImage();
            $image->name = $request->file('file')->getClientOriginalName();
            $image->path = $request->file('file')->storeAs('thumbnail_images', $image->name, 'public');
            $image->url = Storage::disk('public')->url($image->path);
            $product->thumbnailImage()->save($image);
            return response()->json($image);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Delete thumbnail image from server
     * @param Product $product
     * @param ThumbnailImage $thumbnailImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product, ThumbnailImage $thumbnailImage): \Illuminate\Http\JsonResponse
    {
        try {
            $res = Storage::disk('public')->delete($thumbnailImage->path);
            if($res) {
                $thumbnailImage->delete();
            }
            return response()->json($res);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
