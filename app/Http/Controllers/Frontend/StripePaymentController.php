<?php
  
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Stripe;

use DB;
use App\Customer;
use App\Product;

   

   
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe(Request $request)
    {
        $totalPrice=0;
        if($request->session('customer')->has('customer'))
        { 
            $customer=session()->get('customer');       
            $totalPrice = DB::select("select SUM(quantity*product_price) as price from carts where customer_id=".$customer[0]->id);
        }
       
        return view('stripe_payment\stripe',['totalPrice'=>$totalPrice]);
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        $totalPrice=0;
        if($request->session('customer')->has('customer'))
        { 
            $customer=session()->get('customer');       
            $price = DB::select("select SUM(quantity*product_price) as price from carts where customer_id=".$customer[0]->id);
        }
        $price=$price[0]->price;
         $shipping = 5;
         $tax = 0;
         $price =$shipping+$price;
         $tax = ($price*10)/100;
         $price += $tax;
        
        $amount=$price*100;
        //$amount = base64_decode($totalPrice) * 100;
        
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => $amount,
                "currency" => "inr",
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com." 
        ]);
  
        Session::flash('success', 'Payment successful!');
        
        return redirect()->action('Frontend\OrderController@insertOrder');
          
        //return back();
    }
}