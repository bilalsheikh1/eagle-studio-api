<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\File;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    use ApiResponse;

    public function index(Product $product)
    {
        if($product->file != null && $product->file != '')
            return response()->json($product->file->name.' ('.$product->file->size.' MB)');
        return response()->json('');
    }

    public function downloadFile(Product $product)
    {
        try {
            if ($product->file != null && $product->file != '') {
                return Storage::disk("files")->download($product->file->name);
            }
            return response()->json('Invalid file name or file not found.', 500);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function downloadFileByProductID(Request $request, Product $product)
    {
        try {
            if ($product->file != null && $product->file != '') {
                return Storage::disk("files")->download($product->file->name);
            }
            return response()->json('Invalid file name or file not found.', 500);
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    /**
     * Uploads file to server
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Product $product, Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => ['required', 'mimes:rar']
        ]);
        try {
            $file = new File();
            $file->name = $request->file('file')->getClientOriginalName();
            $file->path = $request->file('file')->storeAs('files', $file->name);
            $file->url = Storage::url($file->path);
            $file->size = $request->file('file')->getSize() / 1e+6;
            $product->file()->delete();
            $product->file()->save($file);
//            '('.$file->size.' MB) '. $file->name
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
