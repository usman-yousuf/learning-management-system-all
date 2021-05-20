$(function(event) {
    $('#activity_type_modal-d').on('click', '.activity_card-d', function(e) {
        let elm = $(this);
        $('.activity_card-d').removeClass('active');
        $(elm).addClass('active');
    });


    // Activity Type Modal Course Nature

    $('#video_course-d').on('click', function(e) {
        $('#hdn_course_nature_selection-d').val('video').attr('value', 'video');
    });

    $('#online_course-d').on('click', function(e) {
        $('#hdn_course_nature_selection-d').val('online').attr('value', 'online');
    });

    $('.btn_activity_modal_next-d').on('click', function(e) {
        let selectedCourseNature = $('#hdn_course_nature_selection-d').val();
        if ('' == selectedCourseNature.trim()) {
            Swal.fire({
                title: 'Error',
                text: 'Please Select a course nature',
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
        } else if (selectedCourseNature == 'online') {
            switchModal('activity_type_modal-d', 'course_details_modal-d');
        } else {
            $('#activity_type_modal-d').modal('show');
        }
    });

});