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

//  Side bar Menu Toggle Script 
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});
//  Side bar Menu Toggle Script End

// upload Image -------
// $(document).ready(function() {
//     $("#input-b9").fileinput({
//         showPreview: false,
//         showUpload: false,
//         elErrorContainer: "#kartik-file1-errors ",
//         allowedFileExtensions: ["jpg", "png", "gif"],
//         //uploadUrl: '/site/file-upload-single '
//     });
// });
// // upload Image End-------
// // upload Image -------
// $(document).ready(function() {
//     $("#input-b99").fileinput({
//         showPreview: false,
//         showUpload: false,
//         elErrorContainer: "#kartik-file-errors ",
//         allowedFileExtensions: ["jpg", "png", "gif"],
//         //uploadUrl: '/site/file-upload-single '
//     });
// });
// upload Image End-------

// profile setting upload image  
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
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

        reader.onload = function(e) {
            $('#education_certificate-d')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
// Education Certificate upload image  end





//  graph  dashboard
jQuery(function($) {
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

// Activity Modal

//Activity Modal Hover

$('#video_course-d').hover(function() { // hover in
    $('#video_course-d h6').css('color', '#fff');
    $('#widget-img').attr("src", "assets/preview/shopping-icon.svg");
}, function() { // hover out
    $('#video_card-d h6').css('color', '#C3C3C3');

    $('#widget-img').attr("src", "assets/preview/shopping_icon.svg");
});


$('#online_course-d').hover(function() { // hover in
    $('#online_course-d h6').css('color', '#fff');
    $('#online_course_img-d').attr("src", "assets/preview/video-player.svg");
}, function() { // hover out
    $('#course-d h6').css('color', '#C3C3C3');

    $('#online_course_img-d').attr("src", "assets/preview/video-icon.svg");
});


// Activity Type Modal Course Nature 

$('#video_course-d').on('click', function(e) {
    $('#hdn_course_nature_selection-d').val('video').attr('value', 'video');
});

$('#online_course-d').on('click', function(e) {
    $('#hdn_course_nature_selection-d').val('online').attr('value', 'online');
});

$('.custom_button-d').on('click', function(e) {
    let selectedCourseNature = $('#hdn_course_nature_selection-d').val();
    if (selectedCourseNature == 'video') {

        $('#activity_type_modal-d').modal('hide')
        $('#video_course_details_modal-d').modal('show');
    } else if (selectedCourseNature == 'online') {
        $('#activity_type_modal-d').modal('hide')
        $('#course_details_modal-d').modal('show');
    } else {
        $('#activity_type_modal-d').modal('show');
    }
});



// Activity Modal End

// switch modal 

function switchModal(source, target, is_reset = false) {
    $('#' + source).modal('hide');
    if (is_reset) {
        let reset_form = $('#' + target).find('form');
        $(reset_form).each(function(index, form) {
            $(form)[0].reset();
        });
    }
    setTimeout(function() {
        $('#' + target).modal('show');
    }, 400);
}

// Course Fee Detail

function showHideCourseInfo() {
    if (document.getElementById('hide_detail-d').checked) {
        document.getElementById('course_detail-d').style.display = 'none';
        document.getElementById('handout_section-d').style.display = 'none';
    } else if (document.getElementById('show_detail-d').checked) {
        document.getElementById('course_detail-d').style.display = 'block';
        document.getElementById('handout_section-d').style.display = 'block';
    }
}

// validation of Form


$(function(event) {

    // Validate course detail form start
    $('#course_detail_form-d').validate({
        ignore: ".ignore",
        rules: {
            course_name: {
                required: true,
                // min: 1,
            },
            course_category: {
                required: true,
                // min: 0,
                // max: 59,
            },
            comment_text: {
                required: true,
                // minlength: 5,
            }
        },
        messages: {
            course_name: {
                required: "Name is Required",
                min: "Hour Should have atleast 1 characters",
            },
            course_category: {
                required: "category are Required",
                min: "Minute Should be atleast 1",
                max: 'Minute value cannot exceed 59'
            },
            comment_text: {
                required: "comment is Required.",
                minlength: "Title Should have atleast 8 characters",
            },
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate course detail form End

    // Validate course outline form
    $('#course_outline_form-d').validate({
        ignore: ".ignore",
        rules: {
            hours: {
                required: true,
                min: 1,
            },
            minutes: {
                required: true,
                min: 0,
                max: 59,
            },
            title: {
                required: true,
                minlength: 5,
            }
        },
        messages: {
            hours: {
                required: "Hours is Required",
                min: "Hour Should have atleast 1 characters",
            },
            minutes: {
                required: "Minutes are Required",
                min: "Minute Should be atleast 1",
                max: 'Minute value cannot exceed 59'
            },
            title: {
                required: "Title is Required.",
                minlength: "Title Should have atleast 8 characters",
            },
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate course outline form End

    // Validate Course Slots Form
    $('#course_slots_form-d').validate({
        ignore: ".ignore",
        rules: {
            start_date: {
                required: true,
                // min: 1,
            },
            end_date: {
                required: true,
                // min: 1,

            },
            start_time: {
                required: true,
                // min: 1,
            },
            end_time: {
                required: true,
                // min: 0,

            },

        },
        messages: {
            startdate: {
                required: "Date is Required",
                // min: "Date Should have atleast 1 characters",
            },
            enddate: {
                required: "Date is Required",
                // min: "Date Should Have atleast 1",

            },
            starttime: {
                required: "Time is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            endtime: {
                required: "Time is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate Course Slots Form End

    // Validate course content form
    $('#course_content_form-d').validate({
        ignore: ".ignore",
        rules: {
            handout_title: {
                required: true,
                minlength: 5,
            },

            time: {
                required: true,
                // min: 1,
            },
            url_link: {
                required: true,
                minlength: 5,
            },
            upload_file: {
                required: true,
            },

        },
        messages: {
            handout_title: {
                required: "Title is Required",
                // min: "Date Should have atleast 1 characters",
            },

            time: {
                required: "Time is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            url_link: {
                required: "URL is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            upload_file: {
                required: "Upload File"
            }
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate course content form end

    // Validate Handout content form
    $('#handout_content_form-d').validate({
        ignore: ".ignore",
        rules: {
            handout: {
                required: true,
                minlength: 5,
            },

            link: {
                required: true,
                minlength: 5,
            },


        },
        messages: {
            handout: {
                required: "Title is Required",
                // min: "Date Should have atleast 1 characters",
            },

            link: {
                required: "URL is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },

        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate Handouot content form end

    // Validate Course Fee
    $('#course_fee_form-d').validate({
        ignore: ".ignore",
        rules: {
            fee_USD: {
                required: true,
                minlength: 5,
            },

            discount_USD: {
                required: true,
                minlength: 5,
            },
            fee_PKR: {
                required: true,
                minlength: 5,
            },

            discount_PKR: {
                required: true,
                minlength: 5,
            },

        },
        messages: {
            fee_USD: {
                required: "Currency is Required",
                // min: "Date Should have atleast 1 characters",
            },

            discount_USD: {
                required: "Discount is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            fee_PKR: {
                required: "Currency is Required",
                // min: "Date Should have atleast 1 characters",
            },

            discount_PKR: {
                required: "Discount is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },

        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate Course Fee End


    // Validate video course detail form start
    $('#video_course_detail_form-d').validate({
        ignore: ".ignore",
        rules: {
            course_name: {
                required: true,
                // min: 1,
            },
            course_category: {
                required: true,
                // min: 0,
                // max: 59,
            },
            comment_text: {
                required: true,
                // minlength: 5,
            }
        },
        messages: {
            course_name: {
                required: "Name is Required",
                min: "Hour Should have atleast 1 characters",
            },
            course_category: {
                required: "category are Required",
                min: "Minute Should be atleast 1",
                max: 'Minute value cannot exceed 59'
            },
            comment_text: {
                required: "comment is Required.",
                minlength: "Title Should have atleast 8 characters",
            },
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate video course detail form End

    // Validate video course outline form
    $('#video_course_outline_form-d').validate({
        ignore: ".ignore",
        rules: {
            hours: {
                required: true,
                min: 1,
            },
            minutes: {
                required: true,
                min: 0,
                max: 59,
            },
            title: {
                required: true,
                minlength: 5,
            }
        },
        messages: {
            hours: {
                required: "Hours is Required",
                min: "Hour Should have atleast 1 characters",
            },
            minutes: {
                required: "Minutes are Required",
                min: "Minute Should be atleast 1",
                max: 'Minute value cannot exceed 59'
            },
            title: {
                required: "Title is Required.",
                minlength: "Title Should have atleast 8 characters",
            },
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate video course outline form End

    // Validate video Course Slots Form
    $('#video_course_slots_form-d').validate({
        ignore: ".ignore",
        rules: {
            start_date: {
                required: true,
                // min: 1,
            },
            end_date: {
                required: true,
                // min: 1,

            },
            start_time: {
                required: true,
                // min: 1,
            },
            end_time: {
                required: true,
                // min: 0,

            },

        },
        messages: {
            startdate: {
                required: "Date is Required",
                // min: "Date Should have atleast 1 characters",
            },
            enddate: {
                required: "Date is Required",
                // min: "Date Should Have atleast 1",

            },
            starttime: {
                required: "Time is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            endtime: {
                required: "Time is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate video Course Slots Form End

    // Validate video course content form
    $('#video_course_content_form-d').validate({
        ignore: ".ignore",
        rules: {
            handout_title: {
                required: true,
                minlength: 5,
            },

            time: {
                required: true,
                // min: 1,
            },
            url_link: {
                required: true,
                minlength: 5,
            },
            upload_file: {
                required: true,
            },

        },
        messages: {
            handout_title: {
                required: "Title is Required",
                // min: "Date Should have atleast 1 characters",
            },

            time: {
                required: "Time is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            url_link: {
                required: "URL is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            upload_file: {
                required: "Upload File"
            }
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate video course content form end

    // Validate video Handout content form
    $('#video_handout_content_form-d').validate({
        ignore: ".ignore",
        rules: {
            handout: {
                required: true,
                minlength: 5,
            },

            link: {
                required: true,
                minlength: 5,
            },


        },
        messages: {
            handout: {
                required: "Title is Required",
                // min: "Date Should have atleast 1 characters",
            },

            link: {
                required: "URL is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },

        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate video Handouot content form end

    // Validate video Course Fee
    $('#video_course_fee_form-d').validate({
        ignore: ".ignore",
        rules: {
            fee_USD: {
                required: true,
                minlength: 5,
            },

            discount_USD: {
                required: true,
                minlength: 5,
            },
            fee_PKR: {
                required: true,
                minlength: 5,
            },

            discount_PKR: {
                required: true,
                minlength: 5,
            },

        },
        messages: {
            fee_USD: {
                required: "Currency is Required",
                // min: "Date Should have atleast 1 characters",
            },

            discount_USD: {
                required: "Discount is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            fee_PKR: {
                required: "Currency is Required",
                // min: "Date Should have atleast 1 characters",
            },

            discount_PKR: {
                required: "Discount is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },

        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate video Course Fee End

    // Validate courses outline modal form
    $('#courses_outline_form-d').validate({
        ignore: ".ignore",
        rules: {
            hours: {
                required: true,
                min: 1,
            },
            title: {
                required: true,
                minlength: 5,
            }
        },
        messages: {
            hours: {
                required: "Hours is Required",
                min: "Hour Should have atleast 1 characters",
            },
            title: {
                required: "Title is Required.",
                minlength: "Title Should have atleast 8 characters",
            },
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            // console.log(label, element);
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            console.log('submit handler');
            // $.ajax({
            //     url: $(form).attr('action'),
            //     type: 'POST',
            //     dataType: 'json',
            //     data: $(form).serialize(),
            //     beforeSend: function() {
            //         showPreLoader();
            //     },
            //     success: function(response) {
            //         Swal.fire({
            //             title: 'Success',
            //             text: response.message,
            //             icon: 'success',
            //             showConfirmButton: false,
            //             timer: 2000
            //         }).then((result) => {
            //             window.location.href = APP_URL;
            //         });
            //     },
            //     error: function(xhr, message, code) {
            //         response = xhr.responseJSON;
            //         if (404 == response.exceptionCode) {
            //             let container = $('.pswd_password-d').parent();
            //             if ($(container).find('.error').length > 0) {
            //                 $(container).find('.error').remove();
            //             }
            //             $(container).append("<span class='error'>" + response.message + "</span>");
            //         } else {
            //             Swal.fire({
            //                 title: 'Error',
            //                 text: response.message,
            //                 icon: 'error',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             }).then((result) => {
            //                 // location.reload();
            //                 // $('#frm_donate-d').trigger('reset');
            //             });
            //         }
            //         // console.log(xhr, message, code);
            //         hidePreLoader();
            //     },
            //     complete: function() {
            //         hidePreLoader();
            //     },
            // });
            return false;
        }
    });
    // Validate courses outline modal form end
});