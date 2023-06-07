<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        if(count($roles)>0){
            return response()->json(['data' => $roles], 200);
        }else{
            return response()->json(['message' => 'No Roles Yet add one using POST Method at End Point /role'], 343);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
//    public function store(Store $request): JsonResponse
//    {
//        $validator = Validator::make($request->all(), [
//            'name' => 'required|unique:roles,name|max:255',
//            'description' => 'required',
//        ]);
//        if ($validator->fails()) {
//            $errors = $validator->errors();
//            $errorMessages = [];
//            // Loop through the validation errors
//            foreach ($errors->all() as $error) {
//                $errorMessages[] = $error;
//            }
//            return response()->json(['errors' => $errorMessages], 500);
//        }
//        $validated = $validator->validated();
//        $role = new Role($validated);
//        $role->save();
//        if ($role->save()) {
//            return response()->json(new RoleResource($role), 201);
//        } else {
//            return response()->json(['error' => 'Server Error'], 500);
//        }
//    }
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = new Role($request->all());
        $role->save();
        if ($role->save()) {
            return response()->json(new RoleResource($role), 201);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update($request->all());
        return response()->json(new RoleResource($role), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
            $role->delete();
            return response()->json(['message' => 'deleted successfully'], 203);
    }
}
