<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
//    protected $fillable = ['username'];
//    protected $guarded = ['created_at', 'updated_at'];
    public function createUser(array $user){
        //dd($user);
        User::insert($user);
    }
            
}
