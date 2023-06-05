<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Subcategory extends Model
{
    use HasFactory,Sluggable;
    protected $fillable = [
        'name',
        'category_id'
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
