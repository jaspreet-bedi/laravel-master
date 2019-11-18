function saveShipmentAddress(){
    var token = $('#csrf-token').val();
    var name = $('#name').val();
    var company = $('#company').val();
    var street = $('#street').val();
    var country = $('#country').val();
    var state = $('#state').val();
    var city = $('#city').val();
    var postcode = $('#postcode').val();
    var phone = $('#phone').val();
    var email = $('#email').val();
    
    $.ajax({
        url: site_url + '/frontend/order/saveShipmentAddressInSession',
        data: {name:name,company:company,street:street,country:country,state:state,city:city,postcode:postcode,phone:phone,email:email},
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
                title: 'Shipment Details Saved Successfully',
                showConfirmButton: false,
                timer: 4000,
                width: 300,
                toast: true
            });
            $("#SaveShippment").load(" #SaveShippment >*");
            
        }else{
            Swal.fire({
                position: 'top',
                type: 'error',
                title: 'Problem occured during saving details',
                showConfirmButton: false,
                timer: 4000,
                width: 300,
                toast: true
            });
        }
    });
}



$(document).ready(function(){
    
});
