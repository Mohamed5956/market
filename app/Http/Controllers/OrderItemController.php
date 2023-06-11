<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Orderitem;
use App\Http\Resources\OrderItemResource;
use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use Illuminate\Support\Str;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return OrderItemResource::collection(Orderitem::all());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderItemRequest $request)
    {
        //
        $orderItem = Orderitem::create([
            'order_id' => $request->package_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        if ($orderItem->save()) {
            return response()->json(new OrderItemResource($orderItem), 201);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
        }
//        $Orderitem = Orderitem::create($request->all());
//
//        return new  OrderItemResource($Orderitem);
    }

    /**
     * Display the specified resource.********************************************************
     */
    public function show(Orderitem $Orderitem)
    {
        //
        return new OrderItemResource($Orderitem);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderItemRequest $request, Orderitem $Orderitem)
    {
        //
        $Orderitem->update($request->all());

        return new  OrderItemResource($Orderitem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orderitem $Orderitem)
    {
        //
        $Orderitem->delete();
        return new Response('deleted OrderItem Successfully',204);
    }
}
