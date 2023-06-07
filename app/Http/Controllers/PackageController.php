<?php

namespace App\Http\Controllers;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Models\Packageitem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


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
    public function store(StorePackageRequest $request) : JsonResponse
    {
        $package = new Package($request->all());
        $package->save();
//        dd($package->id);
        $input=$request->data;
         foreach ($input as &$i){
//             dd(gettype($i));

           array_push($i, ['package_id'=> '12']);
         }
//        $input["package_id"]=$package->id;
        dd($input);

        $packageItem = new Packageitem($input);
        dd($packageItem);


        $packageItem->save();

        if ($package->save() && $packageItem->save()) {
            return response()->json(new PackageResource($package), 201);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
            }
    }



//    public function store(StorePackageRequest $request): JsonResponse
//    {
//        $package = new Package($request->all());
//        $package->save();
//
//        $input = $request->data;
//        foreach ($input as $i) {
//            $i['package_id'] = $package->id;
//        }
//
//        $packageItem = new Packageitem($input);
//        $packageItem->save();
//
//        dd($input);
//
//        if ($package->save() && $packageItem->save()) {
//            return response()->json(new PackageResource($package), 201);
//        } else {
//            return response()->json(['error' => 'Server Error'], 500);
//        }
//    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, Package $package) :JsonResponse
    {
        $package->update($request->all());
        if ($package->update($request->all())) {
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
