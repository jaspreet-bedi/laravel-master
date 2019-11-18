<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
//    protected $fillable = ['username'];
//    protected $guarded = ['created_at', 'updated_at'];
    public function createCategory(array $category){
        //dd($user);
        Category::insert($category);
    }
            
}
