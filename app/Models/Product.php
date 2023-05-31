<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'price',
        'quantity',
        'image',
        'trend',
        'subcategory_id'
    ];
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function orderItems(){
        return $this->hasMany(Orderitem::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }
    public function wishlist(){
        return $this->hasMany(Wishlist::class);
    }

}
