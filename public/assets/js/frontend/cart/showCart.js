function updateCart(customer_id,product_id){
    var token = $('#csrf-token').val();
    var qty = $('#'+product_id+'_qty').val();
    $.ajax({
        url: site_url + '/frontend/cart/updateCart',
        data: {customer_id:customer_id,product_id:product_id,qty:qty },
        method: "get",
        headers: {
            'X-CSRF-Token': token 
        },
        
    }).done(function(response){
        if(response == 'true'){
            //bootbox.alert('Cart updatedsuccessfully');
            Swal.fire({
                position: 'top',
                type: 'success',
                title: 'Updated Successfully',
                showConfirmButton: false,
                timer: 4000,
                width: 300,
                toast: true
            });
            $("#cart_qty").load(" #cart_qty >*");
            $("#cart_item").load(" #cart_item >*");
            $("#cart_table").load(" #cart_table >*");
        }else{
            Swal.fire({
                position: 'top',
                type: 'error',
                title: 'Problem occured during updation',
                showConfirmButton: false,
                timer: 4000,
                width: 300,
                toast: true
            });
        }
    });
}

function äpplyCoupon(){
    
    var token = $('#csrf-token').val();
    alert('bp1');
    alert('bp2  '+qty);
    $.ajax({
        url: site_url + '/frontend/cart/applyCoupon',
        data: {},
        method: "get",
        headers: {
            'X-CSRF-Token': token 
        },
        
    }).done(function(response){
        if(response == 'true'){
            alert('CouponCode applied successfully');
            $("#cart_qty").load(" #cart_qty >*");
            $("#cart_item").load(" #cart_item >*");
            $("#cart_table").load(" #cart_table >*");
        }else{
            alert('problem occured in applying coupon code');
        }
    });
    
}

$(document).ready(function(){
    
});
