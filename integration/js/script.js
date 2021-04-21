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


//  graph  dashboard
 jQuery(function ($) {
        var data1 = [12, 3, 4, 2, 12, 3, 4, 17, 22, 34, 54, 67];
        var data2 = [3, 9, 12, 14, 22, 32, 45, 12, 67, 45, 55, 7];
        var data3 = [23, 19, 11, 134, 242, 352, 435, 22, 637, 445, 555, 57];
        var data4 = [13, 19, 112, 114, 212, 332, 435, 132, 67, 45, 55, 7];
            
        $(".chart1").shieldChart({
            exportOptions: {
                image: false,
                print: false
            },
            axisY: {
                title: {
                    text: "Break-Down for selected quarter"
                }
            },
            dataSeries: [{
                seriesType: "line",
                data: data1
            }]
        });
         });
//  graph  dashboard End

// switch modal 

function switchModal(source, target, is_reset=false) {
    $('#'+source).modal('hide');
    if(is_reset){
        let reset_form = $('#'+target).find('form');
        $(reset_form).each(function (index, form){
            $(form)[0].reset();
        });
    }
    setTimeout(function (){
        $('#'+target).modal('show');
    }, 400);
}



