<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Product;
use App\Cart;
use App\CartShipment;
use App\Customer;

class CartController extends Controller
{
    
    public $departments;
    public $allDeptsCategories;
    public $allDepsCatsSubcats;

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
    

    public function addToCart(Request $request){
        
        //$this->initHeaderVars();
            
       // $this->initHeaderVars();
        if($request->session('customer')->has('customer'))
        { 
           $customer=session()->get('customer');     
           $product_id = $request->get('product_id');
           $product=Product::find($product_id);
           
           // Check if product already added in cart for this customer
           $existQty=0;
           $cust_id=$customer[0]->id;
           $itemAlreadyExist = DB::select("select quantity from carts where customer_id=".$cust_id ." and product_id=".$product_id);
           if($itemAlreadyExist!=null)
           $existQty=$itemAlreadyExist[0]->quantity;
           DB::delete("delete from carts where customer_id=".$cust_id ." and product_id=".$product_id);
           
           // Add in Cart Table  
           $cartItem=[];
           $cartItem['customer_id']=$customer[0]->id;
           $cartItem['product_id']=$product->id;
           $cartItem['product_name']=$product->name;
           $cartItem['product_price']=$product->price;
           $cartItem['product_colour']=$product->colour;
           $cartItem['product_size']=$product->size;
           $cartItem['quantity']=$request->get('qty')+$existQty;
           
           $cart=new Cart();
           $cart->insertItem($cartItem);    
           // Inssert item in Cart Table  
           

            
           return "true";
            //return view('frontend/product/showProduct',['departments'=>$departments, 'allDeptsCategories'=>$allDeptsCategories,'allDepsCatsSubcats'=>$allDepsCatsSubcats,'product'=>$product,'cartRows'=>$cartRows,'totalItems'=>$totalItems,'totalPrice'=>$totalPrice]);

           // Reload showProductPage  
        } 

        else
        {
            return "false";
          #return redirect()->route('frontend/account/index')->with('status', 'In order to add to cart, Please sign in!!!. If you are new customer Please register');
        }              
    }
    
    public function showCart(Request $request){
        
        
        $this->getHeaderVariables($request);
        return view('frontend/cart/showCart',[
            'departments'=> $this->departments, 
            'allDeptsCategories'=>$this->allDeptsCategories,
            'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
            'cartRows'=>$this->cartRows,
            'totalItems'=>$this->totalItems,
            'totalPrice'=>$this->totalPrice,
            'productImagesArr'=> $this->productImagesArr]); 
    }
    
    public function updateCart(Request $request){
        //dd('1');
        //dd("in updateCart");
          $customer_id = $request->get('customer_id');
          $product_id = $request->get('product_id');
          $qty = $request->get('qty');
          //dd($qty);
          //DB::update('update carts set qty = ? where customer_id = ? and product_id=?',[$qty,$customer_id,$product_id]);
           DB::update('update carts set quantity='.$qty.' where customer_id='.$customer_id.' and product_id='.$product_id);
          return "true";
          
    }
    
    public function applyCoupon(Request $request){
        //dd("in applyCoupon");
       
          return "true";
          
    }
    
    public function checkout(Request $request){
        
          $this->getHeaderVariables($request);
            
          $customer=session()->get('customer');    
          $customer_id=$customer[0]->id;
          $customerObj=Customer::find($customer_id);
          $accountDetails = $customerObj->customerAccountDetails();
          //return view('frontend/cart/checkout',['departments'=>$departments, 'allDeptsCategories'=>$allDeptsCategories,'allDepsCatsSubcats'=>$allDepsCatsSubcats,'cartRows'=>$cartRows,'totalItems'=>$totalItems,'totalPrice'=>$totalPrice,'productImagesArr'=>$productImagesArr]);
          
          return view('frontend/cart/checkout',[
                    'accountDetails'=> $accountDetails, 
                    'departments'=> $this->departments, 
                    'allDeptsCategories'=>$this->allDeptsCategories,
                    'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
                    'cartRows'=>$this->cartRows,
                    'totalItems'=>$this->totalItems,
                    'totalPrice'=>$this->totalPrice,
                    'productImagesArr'=> $this->productImagesArr]); 
    }
    
    public function deleteItem(Request $request){//Request $request,$custid,$prodid){
        //dd($request);
        $customer_id = $request->get('customer_id');
        
        $product_id = $request->get('product_id');
        DB::delete("delete from carts where customer_id=".$customer_id ." and product_id=".$product_id);
        return "true";
        //return redirect()->action('Frontend\CartController@showCart');
        
        
        
    }
    
    
}
