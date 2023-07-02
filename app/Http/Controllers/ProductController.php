<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductsRequest;
use App\Models\Cart;
use App\Models\Product;

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
        $products = Product::All();
        return response()->json(["data" => $products], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductsRequest $request)
    {
        $product = $request->all();

        // Save the image first
        if($request->hasFile("image"))
        {
            try{
                $image = $request->file("image");
                $image_name = "images/products/".time().'.'.$image->extension();
                $image->move(public_path("images/products"), $image_name);

            }catch(Exception $moveImageException)
            {
                return response()->json([
                    'error' => $moveImageException->getMessage()
                ]);
            }
        }
        else
        {
            $image_name = "images/products/product_defualt_image.jpg";
        }
        // Save the product and return a success response
        $product['image'] = $image_name;
        $new_product = Product::create($product);
        return response()->json($new_product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $data = Product::with('reviews')->findOrFail($product->id);
        return response()->json(['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(StoreProductsRequest $request, string $id)
    {
        $new_info = $request->all();
        $product = Product::find($id);

        // Save the image first
        if($request->hasFile("image"))
        {
            try{
                $image = $request->file("image");
                $image_name = "images/products/".time().'.'.$image->extension();
                $image->move(public_path("images/products"), $image_name);
                error_log($image_name);
                $new_info['image'] = $image_name;
            }catch(Exception $moveImageException)
            {
                return response()->json([
                    'error' => $moveImageException->getMessage()
                ]);
            }

        }

        // Save the product and return a success response
        $updated_product = $product->update($new_info);
        if($updated_product)
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
        $product_defualt_image = "images/products/product_defualt_image.jpg";

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
