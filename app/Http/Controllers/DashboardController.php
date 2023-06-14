<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function analysis(){
        $users = User::all()->count();
        $products= Product::all()->count();
        $categories=Category::all()->count();
        $subCategories=Subcategory::all()->count();
        $orders=Order::all()->count();
        $processing_orders=Order::where('status','Processing')->count();
        $OnDelivery_orders=Order::where('status','On delivery')->count();
        $delivered = Order::where('status','Delivered')->count();
        return response()->json([
            'users' => $users,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'products'=>$products,
            'delivered' => $delivered,
            'orders' => $orders,
            'processing_orders' => $processing_orders,
            'OnDelivery_orders' => $OnDelivery_orders,
        ]);
    }
    public function getMostSoldProducts()
    {
        $mostSoldProducts = Product::select('products.id', 'products.name')
            ->join('orderitems', 'products.id', '=', 'orderitems.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderByRaw('SUM(orderitems.quantity) DESC')
            ->limit(5)
            ->get([
                'products.product_id',
                'products.product_name',
                \DB::raw('SUM(orderitems.quantity) as total_quantity'),
                \DB::raw('SUM(orderitems.quantity * order_items.price) as total_sales_amount')
            ]);
        return response()->json([
            'mostSoldProducts'=>$mostSoldProducts
        ]);
    }
    public function getMostUserPay()
    {
        $mostPayingUsers = User::select('users.id', 'users.name','users.lastName','users.phone')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->join('orderitems', 'orders.id', '=', 'orderitems.order_id')
            ->groupBy('users.id', 'users.name','users.lastName','users.phone'g)
            ->orderByRaw('SUM(orderitems.quantity * orderitems.price) DESC')
            ->limit(5)
            ->get([
                'users.id',
                'users.name',
                \DB::raw('SUM(orderitems.quantity * orderitems.price) as total_payment')
            ]);

        // Get the products for each user
        foreach ($mostPayingUsers as $user) {
            $user->products = Product::select('products.id', 'products.name')
                ->join('orderitems', 'products.id', '=', 'orderitems.product_id')
                ->join('orders', 'orderitems.order_id', '=', 'orders.id')
                ->where('orders.user_id', $user->id)
                ->get();
        }

        return response()->json([
            'mostPayingUsers' => $mostPayingUsers
        ]);
    }
}
