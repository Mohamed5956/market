<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\Package;
use App\Models\Packageitem;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;


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
    public function PackageItems($packageId){
        $data = Package::with( 'packageItems.product')->findOrFail($packageId);
        return response()->json(["data" => $data], 200);
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
    public function ProductById($productId)
    {
        $product = Product::findOrFail($productId);
        return response()->json(["Product" => $product], 200);
    }
        public function Categories(){
        $categories = Category::All();
        return response()->json(["Categories" => $categories], 200);
    }
    public function Subcategories($categoryId){
        $subcategories = Subcategory::where('category_id', '=', $categoryId)->get();
        return response()->json(["Subcategories" => $subcategories], 200);
    }
    public function store_order(StoreOrderRequest $request){
        $user = Auth::user();
        $userController = new UserController();
        $tracking_no = 'Order' . time();
        if($user && $user->phone){
            $user->address = $request->address;
            $user->update();
            $order = Order::create([
                'firstName' => $user->name,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $request->address,
                'total_price' => $request->total_price,
                'user_id' => $user->id,
                'tracking_no' => $tracking_no
            ]);
        }elseif ($user){
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->update();
            $order = Order::create([
                'firstName' => $user->name,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'total_price' => $request->total_price,
                'user_id' => $user->id,
                'tracking_no' => $tracking_no
            ]);
        }
        else {
            $order = Order::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'total_price' => $request->total_price,
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
            $product=Product::findOrFail($item['product_id']);
            $product->quantity -= $item['quantity'];
            $product->update();
        }
        if ($order->save()) {
            return response()->json(new OrderResource($order), 201);
        } else {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    public function index_user(){
        $id=Auth::id();
        $order = Order::where('user_id', '=', $id)->get();
        if (count($order) > 0) {
            return response()->json([ 'data' => $order ], 200);
        }else{
            return response()->json(['message' => 'No Data Found.'], 400);
        }
    }
    public function AllSubCategories()
    {
        $subcategories = Subcategory::with('products')->get();
        if (count($subcategories) > 0) {
            return response()->json([ 'data' => $subcategories ], 200);
        }else{
            return response()->json(['message' => 'No Data Found.'], 400);
        }
    }


}
