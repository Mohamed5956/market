<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class subCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Subcategory::All();
        return response()->json(["data" => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = Subcategory::create($request->all());
        return response()->json(["data" => $category], 201);
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
    public function update(Request $request, string $id)
    {
        $category = Subcategory::find($id);
        $new_info = $request->all();

        if($category->update($new_info))
            return response()->json(["message" => "category updated successfully"], 200);
        else
            return response()->json(["message" => "An error occure while updating category"], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Subcategory::find($id);
        if($category->delete())
            return response()->json(["message" => "category deleted successfully"], 200);
        else
            return response()->json(["message" => "An error occure while deleting category"], 400);
    }
}
