<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class UserController extends Controller
{
    //
    public function index()
    {
        try {
            $users = User::with('role')->get();
            $count = $users->count();
            $users_collection = UserResource::collection($users);
            return response()->json([
                'count' => $count,
                'users' => $users_collection,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }
    public function update(Request $request){
        $id = Route::current()->parameter('id');
        $user = User::where('id', $id);
        if($user->update($request->all())){
            return response()->json(['message' => 'Role Updated Successfully'], 200);
        }else{
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    public function destroy()
    {
        $id = Route::current()->parameter('id');
        $user = User::where('id', $id);
        if($user->delete()) {
            return response()->json(['message' => 'Role Updated Successfully', "user"=>$user], 200);
        }else{
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }
}
