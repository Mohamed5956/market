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
        //
        $user = Auth::user();
        $userController = new UserController();
//        dd($user);
        $tracking_no = 'Order' . time();
//        dd($user->phone);
        if($user && $user->phone){
//            dd($user->phone);
            $order = Order::create([
                'name' => $user->firstName,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'total_price' => $request->total_price,
                'user_id' => $user->id,
                'tracking_no' => $tracking_no
            ]);
        }elseif ($user){
//            dd($user);
            $userController->updated();
//            $user->updated($request->all());
//            $order = Order::create([
//                'name' => $user->firstName,
//                'lastName' => $user->lastName,
//                'email' => $user->email,
//                'phone' => $user->phone,
//                'address' => $user->address,
//                'total_price' => $request->total_price,
//                'user_id' => $user->id,
//                'tracking_no' => $tracking_no
//            ]);
        dd($user);
        }
        else {
            dd("order");
            $order = Order::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'total_price' => $request->total_price,
                'user_id' => $request->user_id,
                'tracking_no' => $tracking_no
            ]);
        }
        $orderItems = $request->order_items;
        foreach ($orderItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        if ($order->save()) {
            return response()->json(new OrderResource($order), 201);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
        }

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
