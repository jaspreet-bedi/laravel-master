<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'length', 'width', 'height', 'distance_unit', 'weight', 'mass_unit',
    ];
    
        
    public function product_images(){
        return $this->hasMany('App\ProductsImage');
    }
    
    public function createProduct(array $product){
        //dd($product);
        //dd($user);
        Product::insert($product);
    }
}
