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
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{

    public function register(CreateUserRequest $request)
    {
        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            return response()->json(['message' => 'Role not found. Please contact the administrator.'], 500);
        }

        $user = new User($request->all());
        $user->password = Hash::make($request['password']);
        $user->role_id = $userRole->id;
        if($request->hasFile("photo"))
        {
            try{
                $photo = $request->file("photo");
                $photo_name = "images/".time().'.'.$photo->extension();
                $photo->move(public_path("images"), $photo_name);

            }catch(Exception $moveImageException)
            {
                return response()->json([
                    'message' => $moveImageException->getMessage()
                ]);
            }
        }
        else
        {
            $photo_name = "images/defualt_image.jpg";
        }
        // Save the product and return a success response
        $user['photo'] = $photo_name;
        $user->save();

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            "address" => $user->address,
            'lastName' => $user->lastName,
            'phone'=>$user->phone,
            'email'=>$user->email,
            'role' => $userRole,
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
            return response()->json(['message' => ['email' => ['The provided credentials are incorrect.']]], 500);
        }
        $user = Auth::user();
        $userRole = Auth::user()->role;
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                "address" => $user->address,
                'lastName' => $user->lastName,
                'phone'=>$user->phone,
                'email'=>$user->email,
                'role' => $userRole,
                'token' => $token,
        ]);
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirectUrl('http://127.0.0.1:8000/api/auth/google/callback')->redirect();
//        return response()->json(['redirect_url' => $response]);
    }

    public function googleCallback()
    {
        $user = Socialite::driver('google')->user();
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            // User exists, generate a token and return the response
            $token = $existingUser->createToken('token')->plainTextToken;

            return response()->json([
                'name' => $existingUser->name,
                "address" => $user->address,
                'lastName' => $user->lastName,
                'phone'=>$user->phone,
                'email'=>$user->email,
                'role' => $existingUser->role->name,
                'token' => $token,
                'social_id'=> $user->id,
                'social_type'=> 'google',
            ]);
        }

        // User does not exist, create a new user
        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            return response()->json(['message' => 'Role not found. Please contact the administrator.'], 500);
        }

        $newUser = new User();
        $newUser->name = $user->name;
        $newUser->email = $user->email;
        $newUser->password = Hash::make('my-google'); // Set an empty password for Google-registered users
        $newUser->role_id = $userRole->id;
        $newUser->social_id = $user->id;
        $newUser->social_type = 'google';
        $newUser->save();

        // Generate a token and return the response
        $token = $newUser->createToken('token')->plainTextToken;

        return response()->json([
            'name' => $newUser->name,
            "address" => $user->address,
            'lastName' => $user->lastName,
            'phone'=>$user->phone,
            'email'=>$user->email,
            'role' => $newUser->role->name,
            'social_id'=> $user->id,
            'social_type'=> 'google',
            'token' => $token,
        ]);
    }
}
