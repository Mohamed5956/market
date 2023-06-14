<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::All();
        return response()->json(["data" => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());
        $this->save_image($request->image,$category);
        return response()->json(["data" => $category], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        return response()->json(["data" => $category], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {

        $old_image=  $category->image;
        // $category->update($request->all());
        if($request->image){
            $this->save_image($request->image, $category);
            $this->delete_image($old_image);
        }

        if($category->update($request->all()))
            return response()->json(["message" => "category updated successfully"], 200);
        else
            return response()->json(["message" => "An error occure while updating category"], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $category = Category::find($id);
        $this->delete_image($category->image);
        if($category->delete())
            return response()->json(["message" => "category deleted successfully"], 200);
        else
            return response()->json(["message" => "An error occure while deleting category"], 400);
    }
    private function delete_image($image_name){
        if($image_name !='images/categories/category_defualt_image.jpg'){
            try{
                unlink(public_path('/'.$image_name));
            }catch (\Exception $e){
                echo $e;
            }
        }
    }

    private function save_image($image, $category){
        if ($image){
            $image_name = "images/categories/".time().'.'.$image->extension();
            $image->move(public_path('images/categories'),$image_name);
            }
        else
        {
            $image_name = "images/categories/category_defualt_image.jpg";
        }
        $category->image = $image_name;
        $category->save();
    }

}
