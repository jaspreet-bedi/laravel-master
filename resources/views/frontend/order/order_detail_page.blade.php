@include('frontend/front_layout/header');

<section class="wn__checkout__area section-padding--lg bg__white">
    <div class="container">
        <hr/><br/>
        <div class="row">
            <div class="col-md-12 col-sm-12 ol-lg-12">
                <?php
                if(!empty($orderShipmentDetailArr)){
                    ?>
                    <b>Shipping Address</b>
<!--                    <div class="table-content wnro__table table-responsive" id="cart_table">-->
                        <div>
                            <div>
                                {{$orderShipmentDetailArr['name']}}
                            </div>
                            <div>
                                {{$orderShipmentDetailArr['company']}}
                            </div>
                            <div>
                                {{$orderShipmentDetailArr['street']}}
                            </div>
                            <div>
                                {{$orderShipmentDetailArr['city']}} ,  {{$orderShipmentDetailArr['state']}}, {{$orderShipmentDetailArr['country']}}. Pincode: {{$orderShipmentDetailArr['postcode']}}
                            </div>
                            <div>
                                {{$orderShipmentDetailArr['phone']}} 
                            </div>
                            <div>
                                {{$orderShipmentDetailArr['email']}}
                            </div>
<!--                            <div>
                                {{date('Y m d', strtotime($orderShipmentDetailArr['created_at']))}}
                            </div>-->
                        </div>
<!--                    </div>-->
                    <?php
                }
                ?>
            </div>
        </div>
        <hr/><br/>
        <?php
        if(!empty($orderProductsArr)){
            ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 ol-lg-12">
                    <div class="table-content wnro__table table-responsive" id="cart_table">
                        <table>
                            <thead>
                                <tr style="background-color: lightgray;">
                                    <th>Product Name</th>
                                    <th>Colour</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($orderProductsArr as $productArr){
                                    ?>
                                    <tr>
                                        <td>{{$productArr['product_name']}}</td>
                                        <td>{{$productArr['product_colour']}}</td>
                                        <td>{{$productArr['product_size']}}</td>
                                        <td>{{$productArr['quantity']}}</td>
                                        <td>${{number_format($productArr['product_price'], 2)}}</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <?php
                            $shipping = $orderShipmentDetailArr['shipping'];
                            $tax = 0.00;
                            $price = 0.00 + $shipping;
                            foreach($orderProductsArr as $productArr){
                                $price += $productArr['product_price'];
                            }
                            
                            $tax = ($price*10)/100;
                            $price += $tax;
                            ?>
                            <tfoot style="background-color: lightgray;">
                                <tr>
                                    <td colspan="4" class="text-right">Shipping Charges</td>
                                    <td>${{number_format($shipping, 2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">Tax</td>
                                    <td>${{number_format($tax, 2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Grand Total</b></td>
                                    <td><b>${{number_format($price, 2)}}</b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        
    </div>
</section>

@include('frontend/front_layout/footer');
