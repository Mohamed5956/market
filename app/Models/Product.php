<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    use HasFactory ,SoftDeletes,Sluggable;
    protected $fillable=[
        'name',
        'description',
        'price',
        'quantity',
        'image',
        'trend',
        'subcategory_id',
        'slug'
    ];
    public function sluggable(): array{
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


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
    public function packageItems(){
        return $this->hasMany(Packageitem::class);
    }

}
