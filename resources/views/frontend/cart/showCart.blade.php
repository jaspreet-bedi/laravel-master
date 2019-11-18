@include('frontend/front_layout/header');

        <!-- Start Bradcaump area -->
        <!-- Start Bradcaump area -->
        <div class="ht__bradcaump__area bg-image--3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="bradcaump__inner text-center">
                        	<h2 class="bradcaump-title">Shopping Cart</h2>
                            <nav class="bradcaump-content">
                              <a class="breadcrumb_item" href="index.html">Home</a>
                              <span class="brd-separetor">/</span>
                              <span class="breadcrumb_item active">Shopping Cart</span>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bradcaump area -->
        <!-- cart-main-area start -->
        <div class="cart-main-area section-padding--lg bg--white">
            <div class="container" id="cart_table">
                <div class="row">
                    <div class="col-md-12 col-sm-12 ol-lg-12">
                        <form action="#">               
                            <div class="table-content wnro__table table-responsive" >
                                <table>
                                    <thead>
                                        <tr class="title-top">
                                            <th class="product-thumbnail">Image</th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Total</th>
                                            <th class="product-subtotal">Update Cart</th>
                                            <th class="product-remove">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     @if(!empty($cartRows))    
                                     @foreach ($cartRows as $cartRow) 
                                        <tr>
                                            <?php
                                            $image = !empty($productImagesArr[$cartRow->product_id]) ? 'assets/images/products/'.$cartRow->product_id.'/'.$productImagesArr[$cartRow->product_id] : 'assets/images/products/no_image.jpg';
                                            ?>
                                            <td class="product-thumbnail"><a href="#"><img src="{{url($image)}}" alt="product img"></a></td>
                                            <td class="product-name"><a href="#">{{ $cartRow->product_name }}</a></td>
                                            <td class="product-price"><span class="amount">${{ $cartRow->product_price }}</span></td>
                                            <td class="product-quantity"><input type="number" name="qty" id="{{$cartRow->product_id}}_qty" value="{{  $cartRow->quantity }}"></td>
                                            <td class="product-subtotal">${{ $cartRow->product_price*$cartRow->quantity }}</td>
                                            <td class="product-add-to-cart"><a href="#" onclick="updateCart({{$cartRow->customer_id}},{{$cartRow->product_id}})"> Update</a></td>
                                            <td class="product-remove"><a href="#" onclick="deleteItem({{$cartRow->customer_id}},{{$cartRow->product_id}})">X</a></td>
<!--                                            <td><a href="#" onclick="deleteItem({{$cartRow->customer_id}},{{$cartRow->product_id}})"><i class="zmdi zmdi-delete"></i></a></td>-->
                                            
                                        </tr>
                                @endforeach
                                @endif           
                                    </tbody>
                                </table>
                            </div>
                        </form> 
                        <div class="cartbox__btn">
                            <ul class="cart__btn__list d-flex flex-wrap flex-md-nowrap flex-lg-nowrap justify-content-between">
                                <li><label for="coupon">Coupon Code</label></li>
                                <li><input name="coupon" id="coupon"> </li>
                                <li><a  href="#" onclick="äpplyCoupon()">Apply Code</a></li>
<!--                                <li><a href="#" >Update Cart</a></li>-->
                                <li><a class="btn btn-primary"  href="{{ url('/frontend/cart/checkout') }}">Check Out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 offset-lg-6">
                        <div class="cartbox__total__area">
                            <div class="cartbox-total d-flex justify-content-between">
                                <?php   
                                    if(!empty($totalPrice))
                                    $price=$totalPrice[0]->price;
                                    else
                                    $price="0.00";    
                                ?>
<!--                                <ul class="cart__total__list">
                                    <li>Cart total</li>
                                </ul>
                                <ul class="cart__total__tk">
                                    <li>${{$price}}</li>
                                    <?php $shipping = 5.00; $tax = (($price+$shipping)*10)/100 ; 
                                     ?>
                                </ul>-->
                            </div>
                             
                            <div class="cart__total__amount">
                                <span>Grand Total</span>
                                <span>${{ number_format($price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
        <!-- cart-main-area end -->
		    <!-- End Checkout Area -->
<script src="{{url('assets/js/frontend/cart/showCart.js')}}"></script>
@include('frontend/front_layout/footer');