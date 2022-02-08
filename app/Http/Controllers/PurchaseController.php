<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = Purchase::query()->has("products")->where("user_id", $request->user()->id)->orderByDesc("id")->get();
            foreach ($data as $index => $v)
            {
                $data[$index]->products = $v->products;
            }
            return $this->apiSuccess("", $data);
        } catch (Exception $exception) {
            return $this->apiFailed("", [], $exception->getMessage());
        }
    }

    public function getAllPurchaseData(Request $request)
    {
        try {
            $data = Purchase::query()->has("products")->has("user")->with("user")->orderByDesc("id")->paginate($request->pageSize);
//            foreach ($data as $index => $v)
//            {
//                $data[$index]->products = $v->products;
//            }
            return $this->apiSuccess("",$data);
        }catch (Exception $exception){
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
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
