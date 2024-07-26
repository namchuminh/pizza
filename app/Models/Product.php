<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_description',
        'detailed_description',
        'original_price',
        'sale_price',
        'image',
        'slug',
        'quantity',
        'tags',
        'category_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
