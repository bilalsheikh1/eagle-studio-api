<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyContorller extends Controller
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
            return $this->apiSuccess("",PrivacyPolicy::query()->first());
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PrivacyPolicy  $privacyPolicy
     * @return \Illuminate\Http\Response
     */
    public function update(PrivacyPolicy $privacyPolicy, Request $request)
    {
        $request->validate([
            "policy" => ["required"]
        ]);
        try {
            $privacyPolicy->policy = $request->policy;
            $privacyPolicy->save();
            return $this->apiSuccess("Privacy & Policy has been updated",$privacyPolicy);
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
