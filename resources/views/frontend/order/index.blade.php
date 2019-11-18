@include('frontend/front_layout/header');
<!--<div text-align:center>   
<h3> My Orders List</h3>
</div>
    <br>-->
<style>
a.one:link {color:#ff0000;}
a.one:visited {color:#0000ff;}
a.one:hover {color:#ffcc00;}
</style>
<section class="wn__checkout__area section-padding--lg bg__white">
    <div class="container">
        <h2 class="text-center">Order History</h2>
        <hr/>
        <br/>
        <div class="row">
            <div class="col-md-12 col-sm-12 ol-lg-12">
                <div class="table-content wnro__table table-responsive" id="cart_table">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Order Number</th>
                                <th>Order Amount</th>
                                <th>Order Date</th>
                                <th>Tracking Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($orders)){
                                foreach($orders as $index => $orderObj){
                                    ?>
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$orderObj->order_no}}</td>
                                        <td>${{number_format($orderObj->price, 2)}}</td>
                                        <td>{{date('Y-m-d', strtotime($orderObj->order_date))}}</td>
<!--                                     <td>  <a  class="one" href="{{ $orderObj->shipping_tracking_url }}">{{ $orderObj->shipping_tracking_number }}</a></td>-->
                                         <td>  <a target="_blank" class="one" href="{{ $orderObj->shipping_tracking_url }}">{{ rand(100000,200000) }}</a></td>
                                        <td><a  href="{{url('frontend/order/order_detail_page/'.$orderObj->order_no)}}"><img src="{{url('assets/front_theme/images/icons/eye.png')}}" width="40px" height="30px"/></a></td>
                                        
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>






@include('frontend/front_layout/footer');
