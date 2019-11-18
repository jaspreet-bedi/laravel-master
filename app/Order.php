<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;

class Order extends Model
{
    
//    protected $fillable = ['username'];
//    protected $guarded = ['created_at', 'updated_at'];
    public function insertItem(array $orderItem){
        //dd($user);
        Order::insert($orderItem);
    }
            
}
