<?php

namespace App\Http\Controllers;

use App\Models\FeaturedImage;
use App\Models\Product;
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

//        $image = getimagesize($request->file('file'));
//        $width = $image[0];
//        $height = $image[1];
//        return response()->json([$width, $height]);
//        $request->validate([
//            'file' => ['required', 'dimensions:width=650,height=290']
//        ]);
        try {
            $image = new FeaturedImage;
            $image->name = $request->file('file')->getClientOriginalName();
            $image->path = $request->file('file')->storeAs('featured_images', $image->name, 'public');
            $image->url = Storage::disk('public')->url($image->path);
            $product->featuredImage()->save($image);
            return response()->json($image);
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
