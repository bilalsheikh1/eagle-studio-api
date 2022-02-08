<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class TermsConditionController extends Controller
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
            return $this->apiSuccess("",TermsCondition::query()->get());
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TermsCondition  $termsCondition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TermsCondition $termsCondition)
    {
        try {
            $termsCondition = TermsCondition::query()->where("type", $request->type)->first();
            $termsCondition->terms_condition = $request->terms_condition;
            $termsCondition->save();
            return $this->apiSuccess("Terms Condition {$termsCondition->type} has been updated");
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }

}
