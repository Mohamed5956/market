<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=>$this->name,
            'lastName'=>$this->lastName,
            'role_id'=> new RoleResource(($this->whenLoaded('role'))),
            'address'=>$this->address,
//            'address2'=>$this->address2,
            'email'=>$this->email,
            'phone'=>$this->phone,
        ];
    }
}
