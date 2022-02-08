<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashbaordController extends Controller
{

    use ApiResponse;

    public function index()
    {
        try {
            $allUsers = User::query()->where("is_admin","!=","1")->count();
            $activeUser = User::query()->where("is_admin","!=","1")->where("active", 1)->count();
            $deActiveUser = User::query()->where("is_admin","!=","1")->where("active", 0)->count();
            $successOrder = Order::query()->where("status", 1)->count();
            $cancelOrder = Order::query()->where("status", 2)->count();
            $inProgressOrder = Order::query()->where("status", 0)->count();
            $approvedRequest = Product::query()->where('status', 1)->count();
            $pendingRequest = Product::query()->where('status', 0)->count();
            $rejectedRequest = Product::query()->where('status', 2)->count();
                return $this->apiSuccess("",["inProgressOrder" => $inProgressOrder, "allUsers" => $allUsers, "activeUser" => $activeUser, "deActiveUser" => $deActiveUser, "successOrder" => $successOrder, "cancelOrder"=> $cancelOrder, "approvedRequest" => $approvedRequest, "rejectedRequest" => $rejectedRequest, "pendingRequest" => $pendingRequest]);
        } catch (Exception $exception){
            return $this->apiFailed("",[],$exception->getMessage());
        }
    }
}
