<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Screenshot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScreenshotController extends Controller
{

    public function index(Product $product, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json($product->screenshots);
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
        $request->validate([
            'file' => ['required']
        ]);
        try {
            $data=[];
            if(gettype($request->file('file')) == "object"){
                $image = new Screenshot();
                $image->name = $request->file('file')->getClientOriginalName();
                $image->path = $request->file('file')->storeAs('screenshots', $image->name, 'public');
                $image->url = Storage::disk('public')->url($image->path);
                $product->screenshots()->save($image);
                $data[]=$image;
            }
            foreach ($request->file('file') as $file) {
                $image = new Screenshot();
                $image->name = $file->getClientOriginalName();
                $image->path = $file->storeAs('screenshots', $image->name, 'public');
                $image->url = Storage::disk('public')->url($image->path);
                $product->screenshots()->save($image);
                $data[]=$image;
            }
            return response()->json($data);
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
