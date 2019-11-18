<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Subcategory;
use DB;

class SubcategoriesController extends Controller
{
    //
	
        
        public function createSubcategory(){
            $departments = DB::select('select * from departments');
            $categories = DB::select('select * from categories');
            return view('subcategories/createSubcategory',['departments'=>$departments],['categories'=>$categories]);
            
            
        }
        
        public function saveSubcategory(Request $request){
           
            $arrSubCat=[];
            $arrSubCat['department_id']=$request->get('departments');
            $arrSubCat['category_id']=$request->get('categories');
            $arrSubCat['name']=$request->get('name');
            $arrSubCat['description']=$request->get('description');
                dd("bp1 inSubCat");        
            //User::insert($arrUsr);
            $subCat=new Subcategory();
            $subCat->createSubcategory($arrSubCat);
            return redirect()->action('UsersController@index');    
                    
        }               
        
}