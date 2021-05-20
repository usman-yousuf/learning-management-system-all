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
    $('#nav-tabContent').on('click', '.course_detail_btn-d', function(e) {
        switchModal('nav-tabContent', 'nav_course_outline');
    })

});