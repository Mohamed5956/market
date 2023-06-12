<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductsRequest;
use App\Models\Product;

use Exception;
use Illuminate\Http\Request;

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
        if($request->hasFile("productImage"))
        {
            try{
                $image = $request->file("productImage");
                $image_name = "public/images/products/".time().'.'.$image->extension();
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
            $image_name = "public/images/products/product_defualt_image.jpg";
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
        if($request->hasFile("productImage"))
        {
            try{
                $image = $request->file("productImage");
                $image_name = "public/images/products/".time().'.'.$image->extension();
                $image->move(public_path("images/products"), $image_name);
                $product['image'] = $image_name;

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
            return response()->json(["message" => "Product has been updated succesfully"], 200);
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
        $product_defualt_image = "public/images/products/product_defualt_image.jpg";

        if($product["image"] != $product_defualt_image)
            unlink(public_path($product_image));


        if($product->delete())
            return response()->json(["success" =>  "product has been trashed"], 200);

    }

}
