<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wishlist = Wishlist::with('user', 'product')->where('user_id', Auth::id())->get();
        if (count($wishlist) <= 0) {
            return response()->json(['error' => 'No Data Found.'], 404);
        }else{
            $wishlist_collection = WishlistResource::collection($wishlist);
            return response()->json(['data'=>$wishlist_collection], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if($this->check_for_existence($request->product_id, Auth::id())){
            $existing_wishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)->first();
            $existing_wishlist['prod_qty'] = $existing_wishlist['prod_qty'] + 1;
            if ($existing_wishlist->update()){
                return response()->json(['message'=>'Cart updated successfully'], 200);
            }else{
                return response()->json(['error' => 'An error occurred. Please try again.'], 500);
            }
        }else {
            $wishlist['user_id'] = Auth::id();
            $wishlist['product_id'] = (int)$request->product_id;
            $new_wishlist = Wishlist::create($wishlist);
            if ($new_wishlist) {
                return response()->json(["data" => $new_wishlist], 200);
            } else {
                return response()->json(["error" => "Internal Server Error"], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wishlist = Wishlist::where('id', $id)->where('user_id', Auth::id())->delete();
        $remainingData = Wishlist::where('user_id', Auth::id())->get();
        return response()->json(['data' => $remainingData, 'message' => 'Item deleted'], 200);
    }

    public function delete_all(){
        $wishlist = new Wishlist();
        $wishlist->where('user_id', Auth::id())->delete();
        $remainingData = Wishlist::where('user_id', Auth::id())->get();
        return response()->json(['data' => $remainingData, 'message' => 'Wishlist deleted'], 200);
    }


    private function check_for_existence($product_id, $user_id)
    {
        $existing_wishlist = Wishlist::where('user_id', $user_id)
            ->where('product_id', $product_id)->first();

        if ($existing_wishlist)
            return true;

        return false;

    }
}
