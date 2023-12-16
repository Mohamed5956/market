<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductsRequest;
use App\Models\Cart;
use App\Models\Product;

use App\Models\Subcategory;
use Exception;
use Illuminate\Http\Request;
use Spatie\Ignition\Tests\TestClasses\Models\Car;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('subcategory')->get();
        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                "id"=> $product->id,
            "name" => $product->name,
            "slug" => $product->slug,
            "description" => $product->description,
            "price" => $product->price,
            "discount" => $product->discount,
            "quantity" => $product->quantity,
            "image" => $product->image,
            "trend" => $product->trend,
            "subcategory_id" => $product->subcategory_id,
                "subcategory_name" => $product->subcategory->name ,
            ];
        }
        return response()->json(["data" => $formattedProducts], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductsRequest $request)
    {
        $product = Product::create($request->all());
        $product->image = $request->image;
        $product->save();
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $data = Product::with('reviews')->findOrFail($product->id);
        $sub_category=Subcategory::find($data->subcategory_id);
        $data->sub_category_name=$sub_category->name;
        return response()->json(['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(StoreProductsRequest $request, string $id)
    {
        $product = Product::find($id);
        if($request->image) {
            $product->image = $product->image;
            $product->save();
        }
        if($product->update($request->all()))
            return response()->json($product, 201);
        else
            return response()->json(["message" => "An error occur while updating"], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product_image = str_replace("public", "", $product["image"]);
        $product_defualt_image = "images/defualt_image.jpg";

        if($product["image"] != $product_defualt_image)
            unlink(public_path($product_image));


        if($product->delete())
            return response()->json(["success" =>  "product has been trashed"], 200);

    }


    public function increment_prod_qty($product_id, $user_id){
        $cart_record = Cart::where('product_id', $product_id)
            ->where('user_id',$user_id)
            ->first();
        $cart_record->prod_qty += 1;
        $cart_record->update();
        return response()->json(['message'=>'Quantity increased', 'qty'=>$cart_record->prod_qty],200);
    }


    public function decrement_prod_qty($product_id, $user_id){
        $cart_record = Cart::where('product_id', $product_id)
            ->where('user_id',$user_id)
            ->first();
        $cart_record->prod_qty -= 1;

        if ($cart_record->prod_qty == 0){
            $cart_record->delete();
            return response()->json(['message'=>'Product has been deleted'],200);
        }

        $cart_record->update();
        return response()->json(['message'=>'Quantity decreased', 'qty'=>$cart_record->prod_qty],200);
    }
}
