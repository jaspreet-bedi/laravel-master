<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use DB;

class ShopgridController extends Controller
{
    
     public function __construct(HeaderController $headerController)
    {
        
        $this->headerController = $headerController;    
    }
    
    public function getHeaderVariables(Request $request){
        $this->departments=$this->headerController->getDepartments($request);
        $this->allDeptsCategories=$this->headerController->getAllDeptsCats($request);
        $this->allDepsCatsSubcats=$this->headerController->getAllDepsCatsSubcats($request);
        $this->cartRows=$this->headerController->getCartRows($request);
        $this->totalItems=$this->headerController->getTotalItems($request);
        $this->totalPrice=$this->headerController->getTotalPrice($request);
        $this->productImagesArr=$this->headerController->getProductImagesArr($request);
    }

    public function index(Request $request,$subcat_id){
        
        
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
                foreach($subcats as  $subcat)
                {
                    $subcatid=$subcat->id;
                    if($subcatid==$subcat_id)
                    {
                    $products = DB::select("select * from products where department_id=".$deptid ." and category_id=".$catid." and subcategory_id=".$subcatid);
                    }
                    
                }
            }     
           
        }  
       // var_dump($products);die;
        
        $imageFileNames=[];
         foreach($products as  $product)
        {
             
            $prodid=$product->id;
            $imageFileArray = DB::select("select filename from products_images where product_id=".$prodid." and isprimary=1");
            if(!empty($imageFileArray)){
                $imageFIleNameObj=$imageFileArray[0];
                $imageFileName=$imageFIleNameObj->filename;
                //dd($imageFileName);die();
                $imageFileNames[$prodid]=$imageFileName;    
            }
                     
            //dd($imageFileNames);die();
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
        return view('frontend/shopgrid/index',['departments'=>$departments,
            'allDeptsCategories'=>$allDeptsCategories,
            'allDepsCatsSubcats'=>$allDepsCatsSubcats,
            'products'=>$products,
            'imageFileNames'=>$imageFileNames,
            'cartRows'=>$cartRows,
            'totalItems'=>$totalItems,
            'totalPrice'=>$totalPrice,
            'productImagesArr'=>$productImagesArr]);
            
    }
}