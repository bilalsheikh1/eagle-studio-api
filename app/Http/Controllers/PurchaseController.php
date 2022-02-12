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

}
