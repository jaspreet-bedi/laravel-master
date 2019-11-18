function bindImageUploadButton() {
    $('#add_image_button').click(
            function () {
                $('#upload_image').trigger('click');
            });
}

function getImageToCrop() {
    $image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
            width: 400,
            height: 500,
            type: 'square'
        },
        boundary: {
            width: 500,
            height: 600
        }
    });
    $('#upload_image').on('change', function () {
        var reader = new FileReader();
        reader.onload = function (event) {
            $image_crop.croppie('bind', {
                url: event.target.result
            }).then(function () {
                original_image = event.target.result;
            });
        }
        reader.readAsDataURL(this.files[0]);
        original_image_type = this.files[0].type;
        original_image_name = this.files[0].name;
        if (original_image_type == 'image/jpeg' || original_image_type == 'image/jpg' || original_image_type == 'image/png' || original_image_type == 'image/gif') {
            $('#uploadimageModal').modal('show');
        } else {
            bootbox.alert("Please select the appropriate file format");
        }
    });

    $('.crop_image').click(function (event) {
        $image_crop.croppie('result', {
            type: 'blob',
            size: 'viewport'
        }).then(function (response) {
            var formData = new FormData();
            formData.append('cropped_image', response, original_image_name);
            formData.append('original_image', $('input[id="upload_image"]')[0].files[0]);
            var token = $('#csrf-token').val();
            $.ajax({
                cache: false,
                contentType: false,
                processData: false,
                url: site_url + '/products/uploadProductImage/' + $('#product_id').val(),
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-Token': token 
                },
                beforeSend: function (request) {
                    $('.crop_image').hide();
                    //blockUI();
                },
                success: function (data) {
                    $('#uploadimageModal').modal('hide');
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        })
        return false;
    });
}

$(document).ready(function () {
    bindImageUploadButton();
    getImageToCrop();
});