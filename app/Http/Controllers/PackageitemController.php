<?php

namespace App\Http\Controllers;

use App\Models\Packageitem;
use App\Http\Requests\StorePackageitemRequest;
use App\Http\Requests\UpdatePackageitemRequest;
use App\Http\Resources\PackageitemResource;
class PackageitemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($packageId)
    {
        $packageitem=Packageitem::findorfail($packageId);
        if(count($packageitem)>0){
            return response()->json(['packages' => $packageitem], 200);
        }else{
            return response()->json(['message' => 'No packageitem :(( '], 343);
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
     */
    public function store(StorePackageitemRequest $request)
    {
        $packageitem = new Packageitem($request->all());
        $packageitem->save();
        if ($packageitem->save()) {
            return response()->json(new PackageitemResource($packageitem), 201);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Packageitem $packageitem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Packageitem $packageitem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageitemRequest $request, Packageitem $packageitem)
    {
        $packageitem->update($request->all());
        if ($packageitem->update($request->all())) {
            return response()->json(new PackageitemResource($packageitem), 201);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Packageitem $packageitem)
    {
        $packageitem->delete();
        if ($packageitem->delete()) {
            return response()->json(['message' => 'deleted successfully'], 203);
        } else {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
}
