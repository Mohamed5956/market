<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->resource instanceof \Illuminate\Validation\Validator) {
            return [
                'errors' => $this->resource->errors(),
            ];
        }
        return [
            // Define the attributes you want to include in the JSON response
            'id' => $this->id,
            'name' => $this->name,
            'total_price'=>$this->total_price,
//            'package_item'=>new PackageitemResource($this->packageItems),
            // Add other attributes as needed
        ];
    }

}
