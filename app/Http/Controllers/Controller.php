<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function storeImages(Request $request){
        if($request->hasFile("image"))
        {
            $image = $request->file("image");
            $image_name = "images/".time().'.'.$image->extension();
            try{
                $image->move(public_path("images"), $image_name);
            }catch(Exception $moveImageException)
            {
                return response()->json([
                    'message' => $moveImageException->getMessage()
                ]);
            }
            return response()->json(['message' => 'Image uploaded successfully', 'image_name' => $image_name], 200);
        }
        else
        {
            $image_name = "images/defualt_image.jpg";
            return response()->json(['message' => 'Image uploaded successfully', 'image_name' => $image_name], 200);
        }
    }
}
