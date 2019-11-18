@include('frontend/front_layout/header');

<br><br><br><br><br><br><br><br>
<div class="container">
    <h3>Select Payment Method</h3>
    <br><br><br>

<!--    <form action="{{URL::to('/frontend/shippo/store')}}">-->
<form action="{{URL::to('/frontend/order/processPaymentMethod')}}">
        {{ csrf_field() }}
        <div class='row'>
            <div class='col-xl-8 col-xl-offset-1'>
            <table>
            <tr>
<!--                <td>
                    <img src="" alt="">
                    
                </td>-->
                <td width="10%">
                    <input type="radio" class="pull-right" name="paymentMethod" value="Card">
                    Debit/Credit Card
                </td>
            </tr>
            
             <tr>
<!--                <td>
                    <img src="" alt="">
                    
                </td>-->
                <td width="10%">
                    <input type="radio" class="pull-right" name="paymentMethod" value="COD">
                    Cash on Delivery
                </td>
            </tr>
            
        </table>
                <br/>
                <button class="btn btn-primary pull-right">Continue</button>
        </div>
       
        </div>
         
        
    </form>
</div>

<br><br><br><br><br><br><br><br>
@include('frontend/front_layout/footer');


