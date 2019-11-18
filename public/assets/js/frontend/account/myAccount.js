function validateForm(){
   
    $("#account_details_form").validate({
   // $("form[name='registration_form']").validate({
        rules: {
            
            name: {
                required: true
            },
            
            company: {
                required: true
            },
            
            country: {
                required: true
            },
            
            state: {
                required: true
            },
            
            city: {
                required: true
            },
            
            street: {
                required: true
            },
            
            phone: {
                required: true,
                minlength: 10,
                maxlength: 10
            },
            postcode: {
                required: true
            }
        },
        messages: {
            
            name: {
                required: "Please enter name"
            },
            
            company: {
                required: "Please enter name"
            },
            
            country: {
                required: "Please enter name"
            },
            
            state: {
                required: "Please enter name"
            },
            
            city: {
                required: "Please enter name"
            },
            
            street: {
                required: "Please enter name"
            },
            
            phone: {
                required: "Please enter your mobile number"
            },
            postcode: {
                required: "Please enter your address"
            }
        },
//        submitHandler: function(form) {
//            form.submit();
//        }
    });
}

$(document).ready(function(){
    validateForm();
});

var data = form.serialize();
window.location.href = site_url + "/frontend/home";