<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\EmailDesign;
use Illuminate\Http\Request;

class EmailDesignController extends Controller
{

    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function getEmailFiledByKey($key)
    {
        try {
            $key = EmailDesign::query()->where("key", "{$key}")->first();
            return $this->apiSuccess("", $key);
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmailDesign  $emailDesign
     * @return \Illuminate\Http\Response
     */
    public function update($key,Request $request)
    {
        try {
            $key = EmailDesign::query()->where("key", $key)->first();
            $key->value = $request->value;
            $key->update();
            return $this->apiSuccess("Data has been updated", $key);
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmailDesign  $emailDesign
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailDesign $emailDesign)
    {
        //
    }
}
