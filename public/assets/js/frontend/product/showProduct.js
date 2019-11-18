function addToCart(product_id){
    var token = $('#csrf-token').val();
    var qty = $('#qty').val();
    $.ajax({
        url: site_url + '/frontend/cart/addToCart',
        data: {product_id:product_id,qty:qty },
        method: "get",
        headers: {
            'X-CSRF-Token': token 
        },
        
    }).done(function(response){
        if(response == 'true'){
            Swal.fire({
                position: 'top',
                type: 'success',
                title: 'Product added into cart successfully.',
                showConfirmButton: false,
                timer: 4000,
                width: 300,
                toast: true
            });
            $("#cart_qty").load(" #cart_qty >*");
            $("#cart_item").load(" #cart_item >*");
        }else{
            Swal.fire({
                position: 'top',
                type: 'error',
                title: 'Please Login your account.',
                showConfirmButton: false,
                timer: 4000,
                width: 300,
                toast: true
            }); 
            setTimeout(function(){ 
                window.location.href = site_url+'/frontend/account';
            }, 1500);
             
        }
    });
}

$(document).ready(function(){
    
});
