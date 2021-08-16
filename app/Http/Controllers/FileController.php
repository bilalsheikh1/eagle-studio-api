<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Uploads file to server
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Product $product, Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => ['required', 'mimes:zip,rar']
        ]);
        try {
            $file = new File();
            $file->name = $request->file('file')->getClientOriginalName();
            $file->path = $request->file('file')->storeAs('files', $file->name);
            $file->url = Storage::url($file->path);
            $product->thumbnailImage()->save($file);
            return response()->json($file);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Deletes file from server
     * @param Product $product
     * @param File $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product, File $file): \Illuminate\Http\JsonResponse
    {
        try {
            $res = Storage::delete($file->path);
            if($res) {
                $file->delete();
            }
            return response()->json($res);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
