<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Customer;
use App\Product;
use App\Order;
use App\OrderShipment;
use App\Services\Shipping;
use App\Repositories\CustomerRepository;

class OrderController extends Controller
{
    
    
    public $departments;
    public $allDeptsCategories;
    public $allDepsCatsSubcats;
    private $shipping;
    
   public function __construct(CustomerRepository $customer, Shipping $shipping, HeaderController $headerController, ShippoController $shippoController)
    {
        $this->shipping = $shipping;
        $this->customer = $customer; 
        $this->headerController = $headerController;    
        $this->shippoController = $shippoController;    
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

    public function selectPaymentMethod(Request $request){
        
        $this->getHeaderVariables($request);
        return view('frontend/order/select_payment_method',[
                'departments'=>$this->departments, 
                'allDeptsCategories'=>$this->allDeptsCategories,
                'allDepsCatsSubcats'=>$this->allDepsCatsSubcats,
                'cartRows'=>$this->cartRows,
                'totalItems'=>$this->totalItems,
                'totalPrice'=>$this->totalPrice,
                'productImagesArr'=>$this->productImagesArr]);
        
        
    }
    
    public function processPaymentMethod(Request $request){
           $paymentMethod=$request->paymentMethod;
           if($paymentMethod=="Card")
           return redirect()->action('Frontend\StripePaymentController@stripe');
           else if($paymentMethod=="COD")
           return redirect()->action('Frontend\OrderController@insertOrder');
                  
    }
    
    public function index(Request $request){

         $this->getHeaderVariables($request);
        if($request->session()->has('customer')){
            $customerArr = $request->session()->get('customer');
            foreach($customerArr as $customer){
                $customer_id = $customer->id;
            }
            $orders = DB::select("select order_no, created_at as order_date, ((sum(product_price)+shipping)*110)/100 as price, shipping_tracking_url, shipping_tracking_number from orders where customer_id=".$customer_id." group by order_no desc");
            
            return view('frontend/order/index',[
                'departments'=>$this->departments, 
                'allDeptsCategories'=>$this->allDeptsCategories,
                'allDepsCatsSubcats'=>$this->allDepsCatsSubcats,
                'cartRows'=>$this->cartRows,
                'totalItems'=>$this->totalItems,
                'totalPrice'=>$this->totalPrice,
                'orders'=>$orders,
                'productImagesArr'=>$this->productImagesArr]);
        }else{
            return view('frontend/account/signupLogin',[
                'departments'=>$this->departments, 
                'allDeptsCategories'=>$this->allDeptsCategories,
                'allDepsCatsSubcats'=>$this->allDepsCatsSubcats,
                'cartRows'=>$this->cartRows,
                'totalItems'=>$this->totalItems,
                'totalPrice'=>$this->totalPrice,
                'productImagesArr'=>$this->productImagesArr]);
        
        }
        
         
    }
    
    public function saveShipmentAddressInSession(Request $request){
        
        
           $customer=session()->get('customer');    
           $cust_id=[];
           $cust_id['id']=$customer[0]->id;
           
         // var_dump(dd($request));
           
           $shipmentAddress=[];
           $shipmentAddress['name']=$request->get('name');
           $shipmentAddress['company']=$request->get('company');
           $shipmentAddress['street']=$request->get('street');
           $shipmentAddress['country']=$request->get('country');
           $shipmentAddress['state']=$request->get('state');
           $shipmentAddress['city']=$request->get('city');
           $shipmentAddress['postcode']=$request->get('postcode');
           $shipmentAddress['phone']=$request->get('phone');
           $shipmentAddress['email']=$request->get('email');
           session()->put("shipmentAddress",$shipmentAddress);

          $customer_id=$customer[0]->id;;
          $customerObj=Customer::find($customer_id);
          $customerObj->updateCustomer($cust_id,$shipmentAddress);
           
          // Shipment code strats here.        
          // Either find a user or create a new one
          $cstmr = $this->customer->findOrCreate($request);
          $validate = $this->shipping->validateAddress($cstmr);
          
            // Make sure it's not an invalid address this  could also be moved to a custom validator rule
            if ($validate->object_state == 'INVALID') {
            return back()->withMessages($validate->messages);
           }  
           
           return redirect()->action('Frontend\ShippoController@index');
           //return redirect()->action('Frontend\OrderController@insertOrder');
          
                
    }
    
    public function placeOrder(Request $request){
        
         $this->getHeaderVariables($request);
         $rate=$request->rate;
         
         $temp = explode ("$", $rate);  
         $shipping=$temp[1];
         $rateObjectID=$temp[0];
         
         session()->put("rateObjectID",$rateObjectID);
         session()->put("shipping",$shipping);
        // var_dump(dd($shipping));
      
         return view('frontend/order/placeOrder',[
                'departments'=> $this->departments, 
                'allDeptsCategories'=>$this->allDeptsCategories,
                'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
                'cartRows'=>$this->cartRows,
                'totalItems'=>$this->totalItems,
                'totalPrice'=>$this->totalPrice,
                'productImagesArr'=> $this->productImagesArr,
                'shipping'=>$shipping]);
    }
    
    public function insertOrder(Request $request){
        
        
        // Call to Shipment Gateway
       // var_dump(dd("here ".$this->rateObjectID));
        $transaction=$this->shippoController->store($request);
        $shipping_tracking_url=$transaction['tracking_url_provider'];
        $shipping_tracking_number=$transaction['tracking_number'];
         
        
        $shipping=session()->get('shipping');  
        $cartRows=[];
        if($request->session('customer')->has('customer'))
        { 
            $customer=session()->get('customer');       
            $cartRows = DB::select("select * from carts where customer_id=".$customer[0]->id);
        }
        
          // Insert Item in Order Table  
          $maxOrderNo = DB::select("select max(order_no) as order_no from orders");
          $order_no=$maxOrderNo[0]->order_no+1;
          
           foreach($cartRows as $cartRow)
           {
               
                $orderItem=[];
                $customer=session()->get('customer');    
                 
                
                $orderItem['order_no']=$order_no;
                $orderItem['customer_id']=$customer[0]->id;
                $orderItem['product_id']=$cartRow->product_id;
                $orderItem['product_name']=$cartRow->product_name;
                $orderItem['product_price']=$cartRow->product_price;
                $orderItem['product_colour']=$cartRow->product_colour;
                $orderItem['product_size']=$cartRow->product_size;
                $orderItem['quantity']=$cartRow->quantity;
                $orderItem['shipping']=floatVal($shipping);
                $orderItem['shipping_tracking_url']=$shipping_tracking_url;
                $orderItem['shipping_tracking_number']=$shipping_tracking_number;
                $order=new Order();
                $order->insertItem($orderItem);    
           }
           // Inssert item in Order Table  
           
           // Inssert shipment details  
           $shipmentDetail=[];
           $shipmentDetail=session()->get('shipmentAddress');   
           $shipmentDetail['order_no']=$order_no;
           $shipmentDetail['shipping']=floatVal($shipping);
           
           //var_dump(dd($shipmentDetail));
           $orderShipment=new OrderShipment();
           $orderShipment->insertItem($shipmentDetail);   
           
           $request->session()->forget('shipmentAddress');
         // Inssert shipment details 
           
           
         // Delete Customer Cart
             $customer=session()->get('customer');  
             $custid=$customer[0]->id;
             DB::delete("delete from carts where customer_id=".$custid);
        // Delete Customer Cart   
    
       return redirect('frontend/order/index');
       
        
        #return view('frontend/order/orderDetail',['departments'=>$departments, 'allDeptsCategories'=>$allDeptsCategories,'allDepsCatsSubcats'=>$allDepsCatsSubcats]);
    }
    
    
    public function order_detail_page(Request $request, $order_no){

        $this->getHeaderVariables($request);
        $orderShipmentDetailArr = OrderShipment::where('order_no', $order_no)->get()->toArray();
        $orderShipmentDetailArr = current($orderShipmentDetailArr);
       # dd($orderShipmentDetailArr);
        $orderProductsArr = Order::where('order_no', $order_no)->get()->toArray();
        
        
        return view('frontend/order/order_detail_page',[
                'departments'=> $this->departments, 
                'allDeptsCategories'=>$this->allDeptsCategories,
                'allDepsCatsSubcats'=>   $this->allDepsCatsSubcats,
                'cartRows'=>$this->cartRows,
                'totalItems'=>$this->totalItems,
                'totalPrice'=>$this->totalPrice,
                'orderProductsArr'=>$orderProductsArr,
                'orderShipmentDetailArr'=> $orderShipmentDetailArr,
                'productImagesArr'=>$this->productImagesArr]);
        
    }
            
}

