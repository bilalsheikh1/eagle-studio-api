<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\License;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->apiSuccess("", License::query()->get());
        } catch (Exception $exception){
            return $this->apiFailed($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\License  $license
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            "key" => ["required", "exists:licenses,key"]
        ]);
        try {
            $license = License::query()->where("key", $request->key)->first();
            $license->value = $request->value;
            $license->save();
            return $this->apiSuccess("License {$license->key} has been updated");
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }
}
