function validateForm(){
   
    $("#registration_form").validate({
   // $("form[name='registration_form']").validate({
        rules: {
            email : {
                required: true
            },
            password : {
                required: true
            }
//            confirmPassword: {
//                required: true,
//                //equalTo: "#password"
//            }
           
        },
        messages: {
            email : {
                required: "Please enter email address"
            },
            password : {
                required: "Please enter correct password"
            }
//            confirmPassword: {
//                required: "Please enter value to confirm the password",
//               // equalTo: "Password did not match"
//            }
            
        },
//        submitHandler: function(form) {
//            form.submit();
//        }
    });
}

$(document).ready(function(){
    validateForm();
});
