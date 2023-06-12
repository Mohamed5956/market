<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\Orderitem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
//use App\Http\Resources\UserResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'total_price' => $this->total_price,
            'tracking_no' => $this->tracking_no,
//            'user_id' => new UserResource($this->whenLoaded('user')),

//            'orderItems'=>[
//                'item' => new OrderItemResource($this->orderItems)
//            ],
//            'orderItems'=> OrderItemResource::collection(Orderitem::all())
        ];
    }
}
