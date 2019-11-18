<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Customer;
use App\Product;

class HeaderController extends Controller
{
    
    
    public $departments;
    public $allDeptsCategories;
    public $allDepsCatsSubcats;
    
    public function getDepartments(Request $request){
        
        $departments = DB::select('select * from departments');
        return $departments;
    }
    
      public function getAllDeptsCats(Request $request){
        
          $departments = DB::select('select * from departments');
        
          foreach($departments as  $department)
          {
            $deptid=$department->id;
            $categories = DB::select("select * from categories where department_id=".$deptid);
            $allDeptsCategories[$deptid]=$categories;     
          }
          return $allDeptsCategories;
        
    }
    
      public function getAllDepsCatsSubcats(Request $request){
          $departments = DB::select('select * from departments');
        
        foreach($departments as  $department)
        {
            $deptid=$department->id;
            $categories = DB::select("select * from categories where department_id=".$deptid);
           $allDeptsCategories[$deptid]=$categories;     
           $counter=0;
           foreach($categories as  $category)
            {
               
               $catid=$category->id;
               $subcats = DB::select("select * from subcategories where department_id=".$deptid ." and category_id=".$catid);
               $allDepsCatsSubcats[$deptid][$catid]=$subcats;                             
           }
           
        }
          return $allDepsCatsSubcats;
    }
    
    public function getCartRows(Request $request){
        $cartRows=[];        
        if($request->session('customer')->has('customer'))
        { 
            $customer=session()->get('customer');       
            $cartRows = DB::select("select * from carts where customer_id=".$customer[0]->id);     
        }
         return $cartRows;
    }
    
     public function getTotalItems(Request $request){
        $totalItems=[];
        if($request->session('customer')->has('customer'))
        { 
            $customer=session()->get('customer');       
            $totalItems = DB::select("select SUM(quantity) as quantity from carts where customer_id=".$customer[0]->id);
        }
          return $totalItems;
    }
    
     public function getTotalPrice(Request $request){
        $totalPrice=[];
        if($request->session('customer')->has('customer'))
        { 
            $customer=session()->get('customer');       
            $totalPrice = DB::select("select SUM(quantity*product_price) as price from carts where customer_id=".$customer[0]->id);
        }
         return $totalPrice;
    }
    
    public function getProductImagesArr(Request $request){
        $productImagesArr = [];
        
        if($request->session('customer')->has('customer'))
        { 
        $customer=session()->get('customer');       
        $cartRows = DB::select("select * from carts where customer_id=".$customer[0]->id);
        }
        
        if(!empty($cartRows)){
            foreach($cartRows as $cartRow){
                $productObj = Product::find($cartRow->product_id);
                $imageCollection = $productObj->product_images->where('isprimary', 1);
                $image = '';
                if(!empty($imageCollection)){
                    foreach($imageCollection as $imageObj){
                        $image = $imageObj->filename;
                    }
                }
                $productImagesArr[$productObj->id] = $image; 
            }
        }
        return $productImagesArr;
    }
    
    
    
    
    public function index(Request $request){
        
        
        $departments = DB::select('select * from departments');
        
        foreach($departments as  $department)
        {
            $deptid=$department->id;
            $categories = DB::select("select * from categories where department_id=".$deptid);
           $allDeptsCategories[$deptid]=$categories;     
           $counter=0;
           foreach($categories as  $category)
            {
               
               $catid=$category->id;
               $subcats = DB::select("select * from subcategories where department_id=".$deptid ." and category_id=".$catid);
               $allDepsCatsSubcats[$deptid][$catid]=$subcats;                             
           }
           
        }
       $cartRows=[];
        $totalItems=[];
        $totalPrice=[];
        if($request->session('customer')->has('customer'))
        { 
        $customer=session()->get('customer');       
        $cartRows = DB::select("select * from carts where customer_id=".$customer[0]->id);
        $totalItems = DB::select("select SUM(quantity) as quantity from carts where customer_id=".$customer[0]->id);
        $totalPrice = DB::select("select SUM(quantity*product_price) as price from carts where customer_id=".$customer[0]->id);
        }
        $productImagesArr = [];
        if(!empty($cartRows)){
            foreach($cartRows as $cartRow){
                $productObj = Product::find($cartRow->product_id);
                $imageCollection = $productObj->product_images->where('isprimary', 1);
                $image = '';
                if(!empty($imageCollection)){
                    foreach($imageCollection as $imageObj){
                        $image = $imageObj->filename;
                    }
                }
                $productImagesArr[$productObj->id] = $image; 
            }
        }
        // var_dump($allDepsCatsProducts);die();
        return view('frontend/account/signupLogin',['departments'=>$departments, 'allDeptsCategories'=>$allDeptsCategories,'allDepsCatsSubcats'=>$allDepsCatsSubcats,'cartRows'=>$cartRows,'totalItems'=>$totalItems,'totalPrice'=>$totalPrice,'productImagesArr'=>$productImagesArr]);
            
    }
    
    
    
    
    
    
   
    
}