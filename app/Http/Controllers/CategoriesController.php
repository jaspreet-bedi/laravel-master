<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Category;
use DB;

class CategoriesController extends Controller
{
    //
	
        
        public function createCategory(){
            $departments = DB::select('select * from departments');
            return view('categories/createCategory',['departments'=>$departments]);
            
            
        }
        
        public function saveCategory(Request $request){
           
            $arrCat=[];
            $arrCat['department_id']=$request->get('departments');
            $arrCat['name']=$request->get('name');
            $arrCat['description']=$request->get('description');
                        
            //User::insert($arrUsr);
            $cat=new Category();
            $cat->createCategory($arrCat);
            return redirect()->action('UsersController@index');    
                    
        }
        
      
        
}