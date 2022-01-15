<?php

namespace App\Http\Controllers;

use App\Models\FeaturedImage;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeaturedImageController extends Controller
{

    public function index(Product $product, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($product->featuredImage);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Uploads image to server
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Product $product, Request $request): \Illuminate\Http\JsonResponse
    {
//        $request->validate([
//            'file' => ['required', 'dimensions:width=200,height=100']
//        ]);
        try {
            $product->featuredImage()->delete();
            $image = $request->file;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName =  Carbon::now()->timestamp.'.'.'png';
            if(\File::put( storage_path('app/public/featured_images'). '/' . $imageName, base64_decode($image)) > 0) {
                $featuredImage = new FeaturedImage;
                $featuredImage->name = $imageName;
                $featuredImage->path = "featured_images/{$imageName}";
//                $featuredImage->path = $request->file('file')->storeAs('featured_images', $featuredImage->name, 'public');
                $featuredImage->url = Storage::disk('public')->url($featuredImage->path);
                $product->featuredImage()->save($featuredImage);
                return response()->json($image);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Delete featured image from server
     * @param FeaturedImage $featuredImage
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product, FeaturedImage $featuredImage): \Illuminate\Http\JsonResponse
    {
        try {
            $res = Storage::disk('public')->delete($featuredImage->path);
            if($res) {
                $featuredImage->delete();
            }
            return response()->json($res);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
