<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
//    protected $fillable = ['username'];
//    protected $guarded = ['created_at', 'updated_at'];
    public function createDepartment(array $department){
        //dd($user);
        Department::insert($department);
    }
            
}
