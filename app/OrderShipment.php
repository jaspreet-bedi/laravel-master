<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;

class OrderShipment extends Model
{
//    protected $fillable = ['username'];
//    protected $guarded = ['created_at', 'updated_at'];
    public function insertItem(array $shipmentDetail){
        //dd($user);
        OrderShipment::insert($shipmentDetail);
    }
            
}
