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
        $request->validate([
            "type" => ["required", "string"]
        ]);
        try {
            if($request->type == "all")
                $orders = Order::query()->with("user");
            else if ($request->type == "in-progress")
                $orders = Order::query()->with("user")->where("status", 0);
            else if($request->type == "cancel")
                $orders = Order::query()->with("user")->where("status", 2);
            else if($request->type == "completed")
                $orders = Order::query()->with("user")->where("status", 1);
            if(isset($request->filters) && $request->filters != "null" && $request->filters != "undefined")
                $orders->where("id", $request->filters);
            return $this->apiSuccess("",$orders->where("created_at",'>=',DB::raw("now() - interval 1 month"))->paginate($request->pageSize));
        } catch (Exception $exception){
            return $this->apiFailed("", [],$exception->getMessage());
        }
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

    public function getFilteredOrders(Request $request)
    {
        $request->validate([
            "id" => ["nullable", "exists:orders"]
        ]);
        try {
            $orders = Order::query()->with("user");
            if($request->type == "cancel")
                $orders->where("status", 2);
            else if($request->type == "completed")
                $orders->where("status", 1);
            else if ($request->type == "in-progress")
                $orders->where("status", 0);

            if(!empty($request->id) && $request->id)
                $orders->where("id", $request->id);
            if(!empty($request->date) && $request->date)
                $orders->whereDate("created_at", '>=', $request->date[0])->whereDate("created_at", '<=',$request->date[1]);
            return $this->apiSuccess("",$orders->paginate($request->pageSize));
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
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
        $request->validate([
            "status" => ["required", "numeric"]
        ]);
        try {
            $order->status = $request->status;
            $order->update();
            return $this->apiSuccess("Order Status has been updated");
        } catch (Exception $exception){
            return $this->apiFailed("", [], $exception->getMessage());
        }
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
