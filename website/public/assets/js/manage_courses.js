$(function(event) {
    // Activity Modal - START
    // mark selection
    $('#activity_type_modal-d').on('click', '.activity_card-d', function(e) {
        let elm = $(this);
        // set actuve class to selected nature container
        $('.activity_card-d').removeClass('active');
        $(elm).addClass('active');

        // set hidden field value
        let course_nature = $(elm).attr('data-course_nature');
        $('#hdn_course_nature_selection-d').val(course_nature).attr('value', course_nature);
    });
    // Activity Modal - END


    // click next btn on activity selection modal for course
    $('.btn_activity_modal_next-d').on('click', function(e) {
        let selectedCourseNature = $('#hdn_course_nature_selection-d').val();
        if ('' == selectedCourseNature.trim()) {
            Swal.fire({
                title: 'Error',
                text: 'Please Choose Course Nature',
                icon: 'error',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                // do nothing
            });
            return false;
        }
        if (selectedCourseNature == 'video') {
            switchModal('activity_type_modal-d', 'video_course_details_modal-d');
        } else {
            switchModal('activity_type_modal-d', 'course_details_modal-d');
        }
    });

    // course details modal - START
    $('#nav_course_detail').on('click', '.click_course_image-d', function(e) {
        $(this).parents('.file-loading').find('#upload_course_image-d').trigger('click');
    });
    // course details modal - END

    // course details submit
    $('#frm_course_details-d').validate({
        ignore: ".ignore",
        rules: {
            course_image: {
                required: true
            },
            title: {
                required: true,
                minlength: 3,
            },
            start_date: {
                required: true,
            },
            end_date: {
                required: true,
            },
            course_category_uuid: {
                required: true,
            },
            // description: {
            //     required: true,
            // }
        },
        messages: {
            course_image: {
                required: "Cover Image is Required"
            },
            title: {
                required: "Title is Required",
                minlength: "Title Should have atleast 3 characters",
            },
            start_date: {
                required: "Start Date is Required",
            },
            end_date: {
                required: "End Date is Required",
            },
            course_category_uuid: {
                required: "Category is Required",
            },
            description: {
                required: "Description is Required.",
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
    // $('#nav-tabContent').on('click', '.course_detail_btn-d', function(e) {
    //     switchModal('nav-tabContent', 'nav_course_outline');
    // })

});