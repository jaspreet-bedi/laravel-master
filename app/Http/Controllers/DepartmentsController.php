<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Department;

class DepartmentsController extends Controller
{
    //
	
        
        public function createDepartment(){
            return view('departments/createDepartment');
        }
        
        public function saveDepartment(Request $request){
           
            $arrDep=[];
            $arrDep['name']=$request->get('name');
            $arrDep['description']=$request->get('description');
                        
            //User::insert($arrUsr);
            $dep=new Department();
            
            $dep->createDepartment($arrDep);
            return redirect()->action('UsersController@index');    
                    
        }
}