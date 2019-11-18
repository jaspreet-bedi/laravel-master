<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Product;
use App\Customer;
use App\Http\Requests;
use App\Services\Shipping;
use Illuminate\Http\Request;
use App\Repositories\CustomerRepository;
use App\Http\Controllers\Frontend\HeaderController;

class ShippoController extends Controller
{
    
    private $shipping;
    private $user;

    /**
     * CheckoutController constructor.
     * @param UserRepository $user
     * @param Shipping $shipping
     */
    public function __construct(CustomerRepository $customer, Shipping $shipping, HeaderController $headerController)
    {
        $this->shipping = $shipping;
        $this->customer = $customer; 
        $this->headerController = $headerController;    
    }

    /**
     * Show the checkout page that lists all the shipping rates
     *
     * @return View
     */
    
    public function getHeaderVariables(Request $request){
        $this->departments=$this->headerController->getDepartments($request);
        $this->allDeptsCategories=$this->headerController->getAllDeptsCats($request);
        $this->allDepsCatsSubcats=$this->headerController->getAllDepsCatsSubcats($request);
        $this->cartRows=$this->headerController->getCartRows($request);
        $this->totalItems=$this->headerController->getTotalItems($request);
        $this->totalPrice=$this->headerController->getTotalPrice($request);
        $this->productImagesArr=$this->headerController->getProductImagesArr($request);
    }

    public function index(Request $request)
    {
      
        // Grabbed the logged in user.
        //$user = auth()->user();
        $customer=session()->get('customer');  
        $customer_id=$customer[0]->id;;
        $customerObj=Customer::find($customer_id);

        // Here we are faking a product and this would typically come from the cart and then look it up from the database
        $product = new Product([
            'length'=> '5',
            'width'=> '5',
            'height'=> '5',
            'distance_unit'=> 'in',
            'weight'=> '2',
            'mass_unit'=> 'lb'
        ]);

        // Now that we have all the data lets try to get a list of shipping providers and pricing
        $shippos = $this->shipping->rates($customerObj, $product);
        $shippoArr = (json_decode($shippos, true));
        $ratesArr = $shippoArr['rates'];   
        
        
      //  return view('frontend/shippo/index',['shippos'=>$ratesArr]);
        
      $this->getHeaderVariables($request);
      
         return view('frontend/shippo/index',[
                'shippos'=>$ratesArr, 
                'departments'=> $this->departments, 
                'allDeptsCategories'=>$this->allDeptsCategories,
                'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
                'cartRows'=>$this->cartRows,
                'totalItems'=>$this->totalItems,
                'totalPrice'=>$this->totalPrice,
                'productImagesArr'=> $this->productImagesArr]);
        
    }
    
    public function store($request)
    {
        

        // now that the user has selected the rate build out the shipping label and tracking in this situation the rate is the object_id
       // var_dump(dd("in shippo"));
        $rateObjectID=session()->get('rateObjectID'); 
        $request->session()->forget('rateObjectID');
//        $transaction = $this->shipping->createLabel($request->rate);
       // var_dump(dd($rateObjectID));
        $transaction = $this->shipping->createLabel($rateObjectID);
        $transaction = (json_decode($transaction, true));
      // var_dump(dd($transaction));

        // If it failed then redirect back and tell them whats wrong
        if ($transaction["status"] != "SUCCESS"){
           // var_dump(dd("transaction error"));
            return back()->withMessage($transaction["messages"]);
        }
        

        // At this point we have our transaction with a label url and a tracking number. Typically you'd log this with the order and email them the receipt.
        // For the purpose of this tutorial we will just return a view with a fictional order and receipt..
        //return view('frontend/shippo/thanks',['shipping'=>$transaction]);
        return $transaction;
    }
    
    
}