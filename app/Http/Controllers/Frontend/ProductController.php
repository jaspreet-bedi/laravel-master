<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Product;

class ProductController extends Controller
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

    public function showProduct(Request $request,$id){
        
          
        //  dd($name);
         // dd($quantity);
         // die();
          
        $product=Product::find($id);
        $relatedProductsCollection = Product::where('category_id', $product->category_id)->get();
        $relatedProductsArr = [];
        if(!empty($relatedProductsCollection)){
            foreach($relatedProductsCollection as $relatedProductObj){
                $relatedProductsArr[$relatedProductObj->id]['name'] = $relatedProductObj->name;
                $relatedProductsArr[$relatedProductObj->id]['price'] = $relatedProductObj->price;
                $relatedProductsArr[$relatedProductObj->id]['actual_price'] = $relatedProductObj->actual_price;
                $relatedProductsArr[$relatedProductObj->id]['image'] = '';
                $productImagesCollection = $relatedProductObj->product_images->where('isprimary', 1)->toArray();
                if(!empty($productImagesCollection)){
                    foreach($productImagesCollection as $productImageArr){
                        $relatedProductsArr[$relatedProductObj->id]['image'] = $productImageArr['filename'];
                    }
                }
                
            }
        }
       
        $productImagesCollection = $product->product_images;
        
          //$product=array("name" => $name, "colour" => $colour, "price" => $price, "quantity" => $quantity);
        
        $this->getHeaderVariables($request);

        return view('frontend/product/showProduct',[
           'departments'=> $this->departments, 
            'allDeptsCategories'=>$this->allDeptsCategories,
            'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
            'product'=>$product,
            'cartRows'=>$this->cartRows,
            'totalItems'=>$this->totalItems,
            'totalPrice'=>$this->totalPrice,
            'productImagesCollection'=>$productImagesCollection,
            'productImagesArr'=>$this->productImagesArr,
            'relatedProductsArr'=>$relatedProductsArr]);
        
    }
}