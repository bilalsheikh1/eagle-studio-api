<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
            $orders =Order::query()->join('order_product as op', 'orders.id', '=', 'op.id')
                ->join('products as p', 'p.id', '=', 'op.product_id')
                ->join('users as u', "u.id", '=', "orders.user_id")
                ->where('p.user_id', $request->user()->id)
                ->select(['p.title','p.id as product_id', 'orders.*', 'u.name'])
                ->get();
            return $this->apiSuccess("", $orders->toArray());
        } catch (\Exception $exception){
            return $this->apiFailed("", [],$exception->getMessage());
        }
    }

    public function getOrders(Request $request)
    {
        try {
            $orders = Order::query()->with("user")->paginate($request->pageSize);
            return $this->apiSuccess("",$orders);
        } catch (Exception $exception){
            return $this->apiFailed("", [],$exception->getMessage());
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
    public function getOrderByUser($id, Request $request)
    {
        try {
            $order = Order::query()->with("user")->where("user_id", $id);
            if($request->orderType == "complete-order")
                $order->where("status", "1");
            else if($request->orderType == "cancel-order")
                $order->where("status", "0");
            else if($request->orderType == "pending-order")
                $order->where("status", "2");
            return $this->apiSuccess("",$order->get());
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        try {
            $order->products = $order->products;
            $order->user = $order->user;
            return $this->apiSuccess("Order", $order);
        } catch (\Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
