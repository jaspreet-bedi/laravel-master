<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Product;
use App\ProductsImage;
use DB;

class ProductsController extends Controller
{
    //
	
        
        public function createProduct(){
            $departments = DB::select('select * from departments');
            $categories = DB::select('select * from categories');
            $subcats = DB::select('select * from subcategories');
           // var_dump(dd($subcategories));
            return view('products/createProduct',['departments'=>$departments,'categories'=>$categories,'subcats'=>$subcats]);
            
            
        }
        
        public function saveProduct(Request $request){
           
           
            
            $arrProd=[];
            $arrProd['department_id']=$request->get('departments');
            $arrProd['category_id']=$request->get('categories');
            $arrProd['subcategory_id']=$request->get('subcategories');
            $arrProd['name']=$request->get('name');
            $arrProd['colour']=$request->get('colour');
            $arrProd['size']=$request->get('size');
            $arrProd['quantity']=$request->get('quantity');
            $arrProd['price']=$request->get('price');
            $arrProd['actual_price']=$request->get('actual_price');
            //dd("bp1 inProd");
            //User::insert($arrUsr);
            $prod=new Product();
            $prod->createProduct($arrProd);
            
             return redirect()->action('ProductsController@uploadImage');
                    
        }     
        
        function uploadImage(){
            $prod_id = DB::select("select max(id) as id from products");
            $product_id=$prod_id[0]->id;
            
            return view('products/uploadImage', ['product_id' => $product_id]);
        }
        
        function uploadProductImage($product_id, Request $request) {
            
            $destinationPath=public_path().'\assets\images\products\\'.$product_id;
            
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
               
            }
          $file_name = time().'_'.$_FILES['cropped_image']['name'];
            move_uploaded_file($_FILES['cropped_image']['tmp_name'], $destinationPath . '/' . $file_name);
           $imageData=[];
                $imageData['product_id']=$product_id;
                $imageData['filename']=$file_name;
                $product_images_count = ProductsImage::where('product_id', $product_id)->count();
                
                if($product_images_count == 0)
                $imageData['isprimary']=1;
                else
                $imageData['isprimary']=0;
                $prodImage=new ProductsImage();
                $prodImage->insertEntry($imageData);
        }
        
        
        
        public function storeImage(Request $request){
            
             
            //$request->prodImage->storeAs('logos\1','one.png');  
            
            $prod_id = DB::select("select max(id) as id from products");
            $prod_id=$prod_id[0]->id;
            $destinationPath=public_path().'\assets\images\products\\'.$prod_id;
            
            $files = $request->file('prodImages');
            if($request->hasFile('prodImages'))
            {
                $i=0;
                foreach ($files as $file) {
                $file->move($destinationPath,$file->getClientOriginalName());

                $imageData=[];
                $imageData['product_id']=$prod_id;
                $imageData['filename']=$file->getClientOriginalName();
                if($i==0)
                $imageData['isprimary']=1;
                else
                $imageData['isprimary']=0;
                $prodImage=new ProductsImage();
                $prodImage->insertEntry($imageData); 
                
                $i++;
                }
            }
        return redirect()->action('UsersController@index');    
                   
        }
        
}