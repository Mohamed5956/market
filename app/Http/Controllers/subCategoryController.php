<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class subCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Subcategory::with('category')->get();
        return response()->json(["data" => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubCategoryRequest $request)
    {
        $subcategory = Subcategory::create($request->all());
        $subcategory->image = $request->image;
        $subcategory->save();
        return response()->json(["data" => $subcategory], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Subcategory::find($id);
        return response()->json(["data" => $category], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubCategoryRequest $request, Subcategory $subcategory)
    {
        if($request->image){
            $subcategory->image = $request->image;
            $subcategory->save();
        }
        if($subcategory->update($request->all()))
            return response()->json($subcategory, 201);
        else
            return response()->json(["message" => "An error occure while updating category"], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Subcategory::find($id);
        $this->delete_image($category->image);
        if($category->delete())
            return response()->json(["message" => "category deleted successfully"], 200);
        else
            return response()->json(["message" => "An error occure while deleting category"], 400);
    }
    private function delete_image($image_name){
        if($image_name !='images/defualt_image.jpg' ){
            try{
                unlink(public_path('/'.$image_name));
            }catch (\Exception $e){
                echo $e;
            }
        }
    }

    private function save_image($image, $subcategory){
        if ($image){
            $image_name = "images/".time().'.'.$image->extension();
            $image->move(public_path('images'),$image_name);
        }
        else
        {
            $image_name = "images/defualt_image.jpg";
        }
        $subcategory->image = $image_name;
        $subcategory->save();
    }


}
