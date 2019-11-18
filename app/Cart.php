<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;

class Cart extends Model
{
//    protected $fillable = ['username'];
//    protected $guarded = ['created_at', 'updated_at'];
    public function insertItem(array $cartItem){
        //dd($user);
        Cart::insert($cartItem);
    }
            
}
