$(function(event) {
    // delete notification
    $('.notification_listing_container-d').on('click', '.delete_notification-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.single_notification-d');
        var removeNotification = function() {
            $(container).remove();
        }
        modelName = 'Notification';
        targetUrl = $(elm).attr('data-href');
        postData = {};
        deleteRecord(targetUrl, postData, removeNotification, 'removeNotification', modelName);
    });

    $('.open_add_calendar_activity-d').on('click', function(e) {
        $('#add_calendar_activity_modal-d').modal('show');
    });

    //
    $('#add_calendar_activity_modal-d').on('click', '.calendar_activity_card-d', function(e) {
        let elm = $(this);
        let activity_type = $(elm).attr('data-activity_type');
        $('.calendar_activity_card-d').removeClass('active');
        $(elm).addClass('active');

        $('#add_calendar_activity_modal-d').find('.hdn_activity_type_selection-d').val(activity_type).attr('value', activity_type);
    });

    //
    $('#add_calendar_activity_modal-d').on('click', '.btn_activity_modal_next-d', function(e) {
        let selectedCourseNature = $('.hdn_activity_type_selection-d').val();
        if ('' == selectedCourseNature.trim()) {
            Swal.fire({
                title: 'Error',
                text: 'Please Choose Activity Type',
                icon: 'error',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                // do nothing
            });
            return false;
        }

        if (selectedCourseNature == 'quiz') {
            switchModal('add_calendar_activity_modal-d', 'course_details_modal-d');
        } else {
            switchModal('add_calendar_activity_modal-d', 'modal_add_assignment-d');
        }
    });

    $('#trigger_assignment_media_upload-d').on('click', function(e) {
        $('#upload_assignment_media-d').trigger('click');
    });
});