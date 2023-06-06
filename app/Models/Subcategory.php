<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Subcategory extends Model
{
<<<<<<< HEAD
    use HasFactory, Sluggable;
=======
    use HasFactory,Sluggable;
>>>>>>> c0eb45382f766a0c412892929a5111f8cef0bc57
    protected $fillable = [
        'name',
        'category_id',
        'slug'
    ];
    public function sluggable(): array{
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
}
