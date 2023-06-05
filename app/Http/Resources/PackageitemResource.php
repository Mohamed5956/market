<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageitemResource extends JsonResource
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
            'product_id' => $this->product_id,
            'package_id'=>$this->package_id,
            'quantity'=>$this->quantity,
            'price'=>$this->price,
            // Add other attributes as needed
        ];
    }

}
