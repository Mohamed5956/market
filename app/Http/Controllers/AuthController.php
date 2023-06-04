<?php

namespace App\Http\Controllers;

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
//    use Illuminate\Support\Facades\Hash;

// ...

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
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
        $validated = $validator->validated();
        $userRole = Role::where('name', 'user')->firstOrFail();
        $user = new User();
        $user->name = $validated['name'];
        $user->lastName = $validated['lastName'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role_id = $userRole->id;
        $user->save();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json(['token' => $token], 201);
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
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken('token-name')->plainTextToken;
        return response()->json(['token' => $token]);
    }

}
