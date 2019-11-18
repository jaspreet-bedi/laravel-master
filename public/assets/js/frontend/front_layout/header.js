function deleteItem(customer_id, product_id){
    bootbox.confirm({
    message: "This is a confirm with custom button text and color! Do you like it?",
    buttons: {
        confirm: {
            label: 'Ok',
            className: 'btn-success'
        },
        cancel: {
            label: 'Cancel',
            className: 'btn-danger'
        }
    },
    callback: function (result) {
        if(result)
        {
            console.log('This was logged in the callback: ' + result);
            var token = $('#csrf-token').val();
            $.ajax({
                url: site_url + '/frontend/cart/deleteItem',
                data: {customer_id: customer_id, product_id: product_id },
                method: "post",
                headers: {
                    'X-CSRF-Token': token 
            },

        }).done(function(response){
            if(response == 'true'){
                Swal.fire({
                    position: 'top',
                    type: 'success',
                    title: 'Deleted Successfully',
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
                    title: 'Problem occured.',
                    showConfirmButton: false,
                    timer: 4000,
                    width: 300,
                    toast: true
                });
            }
        });
     
       }
    }
});
    
    
}

$(document).ready(function(){
    
});
