// -----form validation------ 
(function() {
    "use strict";
    window.addEventListener(
        "load",
        function() {
            // Get the forms we want to add validation styles to
            var forms = document.getElementsByClassName("needs-validation");
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener(
                    "submit",
                    function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add("was-validated");
                    },
                    false
                );
            });
        },
        false
    );
})();
// -----form validation end------



// upload Image -------
$(document).ready(function() {
    $("#input-b9").fileinput({
        showPreview: false,
        showUpload: false,
        elErrorContainer: "#kartik-file1-errors ",
        allowedFileExtensions: ["jpg", "png", "gif"],
        //uploadUrl: '/site/file-upload-single '
    });
});
// upload Image End-------
// upload Image -------
$(document).ready(function() {
    $("#input-b99").fileinput({
        showPreview: false,
        showUpload: false,
        elErrorContainer: "#kartik-file-errors ",
        allowedFileExtensions: ["jpg", "png", "gif"],
        //uploadUrl: '/site/file-upload-single '
    });
});
// upload Image End-------

// profile setting upload image  
 function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.profile_img-d')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
// profile setting upload image end 


// Education Certificate upload image 
function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#education_certificate-d')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
// Education Certificate upload image  end


//  Side bar Menu Toggle Script 
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
//  Side bar Menu Toggle Script End 
