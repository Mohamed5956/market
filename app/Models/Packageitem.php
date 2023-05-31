<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packageitem extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'package_id',
        'quantity',
        'price',
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function package(){
        return $this->belongsTo(Package::class);
    }

}
