<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsImage extends Model
{
    use SoftDeletes;
    
    public function product(){
        return $this->belongsTo('App\Product');
    }
    
     public function insertEntry(array $imageData){
        //dd($user);
        ProductsImage::insert($imageData);
    }
      
}
