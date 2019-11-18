<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
//    protected $fillable = ['username'];
//    protected $guarded = ['created_at', 'updated_at'];
    public function createSubcategory(array $subcategory){
        //dd($user);
        Subcategory::insert($subcategory);
    }
            
}
