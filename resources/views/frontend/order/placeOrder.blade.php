  
@include('frontend/front_layout/header');
<br><br><br><br><br>

<div class="container">
   
    <br/><br/>
<!--    <div class=" col-lg-6 col-6 md-mt-40 sm-mt-40">-->
<div class="wn__order__box">
                    <h3 class="onder__title">Your order Summary</h3>

                    
                    <ul class="order__total">
                        <li>Product</li>
                        <li>Total</li>
                    </ul>
                    <?php
                  //  $shipping = 5.00;
                    $tax = 0.00;
          //var_dump(dd($shipping));
                    if (!empty($totalPrice)){
                        $price = floatVal($totalPrice[0]->price)+floatVal($shipping);
                        $tax = $price*10/100;
                        $price += $tax;
                    }else{
                        $price = "0.00";
                    }
                    ?>
                    <ul class="order_product">
                        @if(!empty($cartRows))    
                        @foreach ($cartRows as $cartRow) 
                        <li>{{ $cartRow->product_name }} {{  $cartRow->quantity }}<span>${{ number_format($cartRow->product_price*$cartRow->quantity, 2) }}</span></li>
                        @endforeach
                        @endif  

                    </ul>
                    <ul class="order_product">
<!--                        <li>Cart Subtotal <span></span></li>-->
                        <li>Shipping <span>${{number_format(floatVal($shipping), 2)}}</span></li>
<!--                        <li>Shipping 
                            <ul>
                                <li>
                                    <input name="shipping_method[0]" data-index="0" value="legacy_flat_rate" checked="checked" type="radio">
                                    <label style="margin-bottom: -0.5rem">Flat Rate: ${{number_format(floatVal($shipping), 2)}}</label>
                                </li>
                            </ul>
                        </li>-->
                    </ul>
                    <ul  class="order_product">
                        <li>Tax <span>${{number_format($tax, 2)}}</span></li>
                    </ul>
                    
                    <ul class="total__amount">
                        <li>Order Total <span>${{ number_format($price, 2) }}</span></li>
                    </ul>
                </div>
<br><br>
 <span class="form__btn text-right">
                        <form>
                            <button class="btn btn-primary" formaction="{{URL::to('/frontend/order/selectPaymentMethod')}}">Place Order</button>
<!--                                    <button formaction="{{URL::to('/frontend/order/insertOrder')}}">Cash On Delivery</button>
                        <button formaction="{{URL::to('/frontend/stripe')}}">Debit Card</button>-->
                        </form>
                    </span>
<!--   </div>-->
</div>
   
 
<br><br><br><br><br><br><br><br>
@include('frontend/front_layout/footer');