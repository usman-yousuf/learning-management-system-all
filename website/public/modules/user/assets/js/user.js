$(function(event) {
    // trigger upload wizard for profile image upload
    $('.click_profile_image-d').on('click', function(e) {
        let elm = $(this);
        $('#upload_profile_image-d').trigger('click');
    });

    // trigger upload wizard for profile image upload
    $('.click_certificate_image-d').on('click', function(e) {
        let elm = $(this);
        $(elm).closest('.upload_file_container-d').find('#upload_certificate_image-d').trigger('click');
    });
});


// upload Image -------
// $(document).ready(function() {
//     if ($("#input-b9").length > 0) {
//         $("#input-b9").fileinput({
//             showPreview: false,
//             showUpload: false,
//             elErrorContainer: "#kartik-file1-errors ",
//             allowedFileExtensions: ["jpg", "png", "gif"],
//             //uploadUrl: '/site/file-upload-single '
//         });
//     }
// });
// upload Image End-------
// upload Image -------
// $(document).ready(function() {
//     if ($("#input-b99").length > 0) {
//         $("#input-b99").fileinput({
//             showPreview: false,
//             showUpload: false,
//             elErrorContainer: "#kartik-file-errors ",
//             allowedFileExtensions: ["jpg", "png", "gif"],
//             //uploadUrl: '/site/file-upload-single '
//         });
//     }
// });
// upload Image End-------

// profile setting upload image

// profile setting upload image end


// Education Certificate upload image
function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#education_certificate-d')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
// Education Certificate upload image  end