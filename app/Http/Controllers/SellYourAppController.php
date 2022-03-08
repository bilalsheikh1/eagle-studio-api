<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\SellYourApp;
use Illuminate\Http\Request;

class SellYourAppController extends Controller
{

    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            return $this->apiSuccess("", SellYourApp::query()->first());
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SellYourApp  $sellYourApp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SellYourApp $sellYourApp)
    {
        $request->validate([
            "data" => ["required"]
        ]);
        try {
            $sellYourApp->data = $request->data;
            $sellYourApp->save();
            return $this->apiSuccess("Sell your app has been updated",$sellYourApp);
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }

}
