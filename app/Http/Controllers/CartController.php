<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use Exception;
//use App\Http\Controllers\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Ignition\Tests\TestClasses\Models\Car;
use function Symfony\Component\VarDumper\Dumper\esc;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cart = Cart::where('user_id', Auth::id());

            if ($cart) {
                return response()->json(['error' => 'No Data Found.'], 200);
            }else{
                return response()->json([ 'data' => $cart ], 200);
            }

        }catch (Exception $e){
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
//        try {
//            $cart = new Cart();
//            $cart->create($request->all());
            $cart = Cart::create($request->all());
//            $cart->save();
            return response()->json(['data'=>$cart], 201);
//        }catch (Exception $e){
//            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
//        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
        try {
            $cart = Cart::findOrFail($cart);
            return response()->json(['data' => $cart],200);
        }catch (Exception $e){
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCartRequest $request, Cart $cart)
    {
//        dd(Auth::user());
        if ($request->user_id == 1)
        {
            if($cart->update($request->all())){
                $all_cart = Cart::all();
                return response()->json(["data" => $all_cart, 'message'=>'Cart updated successfully'], 200);
            }else{
                return response()->json(['error' => 'An error occurred. Please try again.'], 500);
            }
        }else{
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart = $cart->delete();
        $remaining_data = Cart::where('user_id', Auth::id());
        return response()->json(['data' => $remaining_data, 'cart' => $cart],200);
    }
}
