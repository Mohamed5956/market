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
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }
    public function update(Request $request){
        $id = Route::current()->parameter('id');
        $user = User::where('id', $id);
        if($request->photo) {
            $user->photo = $request->photo;
            $user->save();
        }
        if($user->update($request->all())){
            return response()->json(['message' => 'Role Updated Successfully'], 200);
        }else{
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }
    public function updateUserData(Request $request){
        $user = User::where('id', $request->id)->first();
        if($request->photo) {
            $user->photo = $request->photo;
            $user->save();
        }
        if($user->update($request->all())){
            return response()->json(['message' => 'UserData Updated Successfully'], 200);
        }else{
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }

    public function destroy()
    {
        $id = Route::current()->parameter('id');

        if (Auth::id() == $id){
            $user = User::where('id', $id)->first();
            if($user->delete()) {
                return response()->json(['message' => 'Role Updated Successfully', "user"=>$user], 200);
            }else{
                return response()->json(['message' => 'An error occurred.'], 500);
            }
        }else{
            return response()->json(['message' => 'Unauthorized to delete this user'], 403);
        }

    }
    public function adminDelete(){
        $id = Route::current()->parameter('id');
            $user = User::where('id', $id)->first();
            if($user->delete()) {
                return response()->json(['message' => 'User deleted Successfully', "user"=>$user], 200);
            }else{
                return response()->json(['message' => 'An error occurred.'], 500);
            }
    }
    public function show($id){
        $user=User::find($id);
        if($user) {
            return response()->json(["user"=>$user], 200);
        }else{
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }
    public function store(CreateUserRequest $request){
        $user = new User($request->all());
        $user->role_id = $request->role_id;
        $user->photo = $request->photo;
        $user->save();
        if ($user->save()) {
            return response()->json(new UserResource($user), 201);
        } else {
            return response()->json(['message' => 'Server Error'], 500);
        }
    }
}
