<?php

namespace App\Http\Controllers;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $packages= Package::all();
        if(count($packages)>0){
            return response()->json(['packages' => $packages], 200);
        }else{
            return response()->json(['message' => 'No packages :(( '], 343);
            }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required | min:3 | max:255',
            'total_price'=>'required | decimal:2 '
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
        $package = new Package($validated);
        $package->save();

        if ($package->save()) {
            return response()->json(new PackageResource($package), 201);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //

    }


    public function updateFromObject($object)
    {
        foreach ($object as $key => $value) {
            $this->{$key} = $value;
        }
//        return response()->json(gettype([$object]));
        return $object;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package) :JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required | min:2 | max:255',
            'total_price'=>'required | decimal:2 '
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
        $updatedPackage = new Package($validated);
        $package = $this->updateFromObject($updatedPackage);
        $package->update();
//        $package->update($updatedPackage);

        return response()->json($package);


        if ($package->update($updatedPackage)) {
            return response()->json(new PackageResource($package), 201);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();
        if ($package->delete()) {
            return response()->json(['message' => 'deleted successfully'], 203);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
        }

    }
}
