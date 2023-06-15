<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(CreateUserRequest $request)
    {
        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            return response()->json(['errors' => 'Role not found. Please contact the administrator.'], 500);
        }

        $user = new User($request->all());
        $user->password = Hash::make($request['password']);
        $user->role_id = $userRole->id;
        $user->save();

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
                'name' => $user->name,
                'role' => $userRole->name,
                'token' => $token,
        ], 201);
    }



    /**
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = [];
            // Loop through the validation errors
            foreach ($errors->all() as $error) {
                $errorMessages[] = $error;
            }
            return response()->json(['errors' => $errorMessages], 500);
        }
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['errors' => ['email' => ['The provided credentials are incorrect.']]], 500);
        }
        $user = Auth::user();
        $userRole = Auth::user()->role;
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'role' => $userRole,
                'token' => $token,
        ]);
    }

}
