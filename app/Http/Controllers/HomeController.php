<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }
    public function Packages(){
        $packages=Package::with('packageItems')->get();
        return response()->json(["Packages" => $packages], 200);
    }
    public function Products(){
        if (isset($_GET['filter'])) {
           if ($_GET['filter']=='trending') {
               $products = Product::where('trend', '=', 1)->get();
               return response()->json(["Products" => $products], 200);
           }else{
               $products = Product::where('subcategory_id', '=', $_GET['filter'])->get();
               return response()->json(["Products" => $products], 200);
           }
        } else {
            $products = Product::All();
            return response()->json(["Products" => $products], 200);
        }
    }
    public function Categories(){
        $categories = Category::All();
        return response()->json(["Categories" => $categories], 200);
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
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
