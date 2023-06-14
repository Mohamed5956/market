<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        try {
            $users = User::all();
            $count = $users->count();
            return response()->json([
                'count' => $count,
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }
    public function update(Request $request, User $user ){
        $user->update($request->all());
    }
}
