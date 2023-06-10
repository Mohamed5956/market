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
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $id=Auth::user()->id;
            $cart = Cart::where('user_id', '=', $id)->get();

            if (count($cart) <= 0) {
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
            $cart['product_id'] = (int) $request->product_id;
            $cart['prod_qty'] = $request->prod_qty;
            $cart['user_id'] = Auth::id();
            $new_cart = Cart::create($cart);
            if($new_cart){
                return response()->json(['data' => $new_cart], 201);
            }else{
                return response()->json(['message'=>'Internal Server Error'], 500);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
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
        $cart['product_id'] = (int) $request->product_id;
        $cart['prod_qty'] = $request->prod_qty;
        $cart['user_id'] = (int) $request->user_id;
        if($cart->update()){
            $all_cart = Cart::all();
            return response()->json(["data" => $all_cart, 'message'=>'Cart updated successfully'], 200);
        }else{
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $delete_item = $cart->delete();
        $remainingData = Cart::where('user_id', Auth::id())->get();
        return response()->json(['data' => $remainingData, 'message' => 'Item deleted'], 200);
    }

    public function delete_all(){
        // Delete all items in the cart
        $cart = Cart::where('user_id', Auth::id())->delete();
        $remainingData = Cart::where('user_id', Auth::id())->get();
        return response()->json(['data' => $remainingData, 'message' => 'All items deleted'], 200);
    }

}
