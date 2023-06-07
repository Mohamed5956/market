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
        $packages=Package::with('packageItems')->get();
//        $packages= PackageResource::collection(Package::all());
        if(count($packages)>0){
            return response()->json($packages, 200);
        }else{
            return response()->json(['message' => 'No packages :(( '], 343);
            }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request)
    {
        $package = Package::create([
            'name' => $request->name,
            'total_price' => $request->total_price,
        ]);
        $packageItems = $request->package_items;
        foreach ($packageItems as $item) {
            $package->packageItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
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
