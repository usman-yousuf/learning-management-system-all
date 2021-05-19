$(function(event) {
    $('#video_course-d').hover(function() { // hover in
        $(this).find('h6').addClass('text-white');
        $(this).addClass('bg_success-s');
        $(this).find('img').attr("src", activity_modal_online_course_icon_url);
    }, function() { // hover out
        $(this).find('h6').removeClass('text-white');
        $(this).removeClass('bg_success-s');
        $(this).find('img').attr("src", activity_modal_online_course_icon_url);

        // $('#widget-img').attr("src", "assets/preview/shopping_icon.svg");
    });




    $('#online_course-d').hover(function() { // hover in
        $(this).find('h6').addClass('text-white');
        $(this).addClass('bg_success-s');
        // $('#online_course_img-d').attr("src", "assets/preview/video-player.svg");
    }, function() { // hover out
        $(this).find('h6').removeClass('text-white');
        $(this).removeClass('bg_success-s');

        // $('#online_course_img-d').attr("src", "assets/preview/video-icon.svg");
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

});