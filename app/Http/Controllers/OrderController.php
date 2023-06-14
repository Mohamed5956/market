<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\UserController;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $orders=Order::with('orderItems')->get();
        if(count($orders)>0){
            return response()->json(["data" => $orders], 200);
        }else{
            return response()->json(['error' => 'No orders :(( ', 'data' => []],  200);
        }
    }


    public function order_user($order_id){
        $order = Order::where('id', '=', $order_id)->first();
        dd($order->user());
//        $user=User::with('orders')->get();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $data = Order::with('user', 'orderItems.product')->findOrFail($order->id);
        return response()->json(['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all());
        return new  OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return new Response('deleted order Successfully',200);
    }
}
