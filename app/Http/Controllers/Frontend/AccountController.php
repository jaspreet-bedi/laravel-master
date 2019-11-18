<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\HeaderController;

use Illuminate\Http\Request;
use DB;
use App\Customer;
use App\Product;

class AccountController extends Controller
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

    public function index(Request $request){
        
         $this->getHeaderVariables($request);
         return view('frontend/account/signupLogin',[
                'departments'=> $this->departments, 
                'allDeptsCategories'=>$this->allDeptsCategories,
                'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
                'cartRows'=>$this->cartRows,
                'totalItems'=>$this->totalItems,
                'totalPrice'=>$this->totalPrice,
                'productImagesArr'=> $this->productImagesArr]);  
    }
    
    public function registerUser(Request $request){
        
        
        //dd($request);die;
        $mailValue=$request->get('email');
        $customer = DB::select("select * from customers where email like '".$mailValue."'");
         if($customer!=null)
         {
             return redirect()->action('Frontend\AccountController@index')->with('registrationStatus', 'Customer Account already exists!!!');
             
         }
        else
        {
        
            $arrCstmr=[];
            $arrCstmr['email']=$request->get('email');
            $arrCstmr['password']=$request->get('password');
            $arrCstmr['name']=$request->get('name');


            $customer=new Customer();
            $customer->createCustomer($arrCstmr);
   

             $customer = DB::select("select * from customers where email='".$request->get('email')."' and password='".$request->get('password')."'");
             if($customer!=null)
             {    

                 //dd("user authenticated successfully");
                 session()->put("customer",$customer);
             }
             
             $this->getHeaderVariables($request);
              return view('frontend/account/InputAccountDetails',[
                    'departments'=> $this->departments, 
                    'allDeptsCategories'=>$this->allDeptsCategories,
                    'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
                    'cartRows'=>$this->cartRows,
                    'totalItems'=>$this->totalItems,
                    'totalPrice'=>$this->totalPrice,
                    'productImagesArr'=> $this->productImagesArr]); 
        }
        
                
    }
    
   public function updateAccountDetails(Request $request){
          
          $customer=session()->get('customer');    
          $customer_id=$customer[0]->id;
          $cust_id=[];
          $cust_id['id']=$customer[0]->id;
          
          
            $customerDetails=[];
            $customerDetails['name']=$request->get('name');
            $customerDetails['company']=$request->get('company');
            $customerDetails['street']=$request->get('street');
            $customerDetails['country']=$request->get('country');
            $customerDetails['state']=$request->get('state');
            $customerDetails['city']=$request->get('city');
            $customerDetails['postcode']=$request->get('postcode');
            $customerDetails['phone']=$request->get('phone');

          $customerObj=Customer::find($customer_id);
          $customerObj->updateCustomer($cust_id,$customerDetails);
          
           return redirect()->action('Frontend\HomeController@index')->with('status', 'Customer Account authenticated successfully!!!');
         // return redirect()->action('Frontend\AccountController@showAccountDetails')->with('status', 'Account Details updated Succesfully!!!');
        
    }
    
     public function showAccountDetails(Request $request){
          
          
         if($request->session('customer')->has('customer'))
        {
          $customer=session()->get('customer');    
          $customer_id=$customer[0]->id;
          $customerObj=Customer::find($customer_id);
          $accountDetails = $customerObj->customerAccountDetails();
  
           $this->getHeaderVariables($request);
              return view('frontend/account/MyAccount',[
                    'accountDetails'=> $accountDetails, 
                    'departments'=> $this->departments, 
                    'allDeptsCategories'=>$this->allDeptsCategories,
                    'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
                    'cartRows'=>$this->cartRows,
                    'totalItems'=>$this->totalItems,
                    'totalPrice'=>$this->totalPrice,
                    'productImagesArr'=> $this->productImagesArr]); 
        }
        else
        {
            $this->getHeaderVariables($request);
            return view('frontend/account/signupLogin',[
                'departments'=> $this->departments, 
                'allDeptsCategories'=>$this->allDeptsCategories,
                'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
                'cartRows'=>$this->cartRows,
                'totalItems'=>$this->totalItems,
                'totalPrice'=>$this->totalPrice,
                'productImagesArr'=> $this->productImagesArr]); 
        }  
        
        
    }
    
    public function authenticateUser(Request $request){
 
         $customer = DB::select("select * from customers where email='".$request->get('email')."' and password='".$request->get('password')."'");
         if($customer!=null)
         {    
             
             //dd("user authenticated successfully");
             session()->put("customer",$customer);
             return redirect()->action('Frontend\HomeController@index')->with('status', 'Customer Account authenticated successfully!!!');
         }
         else{
             //dd("user authentication failed");
             return redirect()->action('Frontend\AccountController@index')->with('loginStatus', 'Customer Account authentication failed. Please try again!!!');
         }       
           
    }
    
    public function signout(){
        session()->forget('customer');
        return redirect()->action('Frontend\AccountController@index');
    }
    
   
    
}