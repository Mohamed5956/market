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
use Illuminate\Support\Str;

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
            return response()->json($orders, 200);
        }else{
            return response()->json(['message' => 'No orders :(( '], 343);
        }

//        return OrderResource::collection(Order::all());
    }

    public function index_user(){
        $id=Auth::id();
        $order = Order::where('user_id', '=', $id)->get();

        if (count($order) > 0) {
            return response()->json([ 'data' => $order ], 200);
        }else{
            return response()->json(['error' => 'No Data Found.'], 400);
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
        $tracking_no = 'Order' . time();
        $order = Order::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_price' => $request->total_price,
            'user_id' => $request->user_id,
            'tracking_no'=>$tracking_no
        ]);
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
        //

        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
        $order->update($request->all());

        return new  OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
        //
        $order->delete();
        return new Response('deleted order Successfully',200);

    }
}
