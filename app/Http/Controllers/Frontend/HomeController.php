<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Product;

class HomeController extends Controller
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

    public function index(Request $request){
        
        
         $this->getHeaderVariables($request);
        
        $newProductsArr = $this->getProducts(true);
        $allProductsArr = $this->getProducts();
        $randomProductsArr = $this->getProducts(false, true);
        return view('frontend/home/index',[
             'departments'=> $this->departments, 
            'allDeptsCategories'=>$this->allDeptsCategories,
            'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
            'cartRows'=>$this->cartRows,
            'totalItems'=>$this->totalItems,
            'totalPrice'=>$this->totalPrice,
            'newProductsArr' => $newProductsArr,
            'allProductsArr' => $allProductsArr,
            'randomProductsArr' => $randomProductsArr,
            'productImagesArr'=>$this->productImagesArr]);
            
    }
    
    public function getProducts($new = false, $random = false){
        $productsArr = [];
        if($new){
            $productsCollection = Product::limit(6)->orderBy('id', 'desc')->get();
        }else if($random){
            $productsCollection = Product::inRandomOrder()->limit(9)->get();
        }else{
            $productsCollection = Product::orderBy('id', 'desc')->get();
        }
        if(!empty($productsCollection)){
            foreach($productsCollection as $productObj){
                $productsArr[$productObj->id]['name'] = $productObj->name;
                $productsArr[$productObj->id]['actual_price'] = $productObj->actual_price;
                $productsArr[$productObj->id]['price'] = $productObj->price;
                $imageCollection = $productObj->product_images->where('isprimary', 1);
                $image = '';
                if(!empty($imageCollection)){
                    foreach($imageCollection as $imageObj){
                        $image = $imageObj->filename;
                    }
                }
                $productsArr[$productObj->id]['image'] = $image; 
          }
        }
        if($new || $random){
            return $productsArr;
        }else{
            $bindedProductsArr = [];
            if(!empty($productsArr)){
                $i = 0;
                $count = 0;
                foreach($productsArr as $id => $productArr){
                    if($count%2 == 0 && $count != 0){
                        $i++;
                    }
                    $bindedProductsArr[$i][$id] = $productArr;
                    $count++;
                }
            }
            return $bindedProductsArr;
        }
        
    }
}