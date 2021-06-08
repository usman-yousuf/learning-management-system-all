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

    // popup calendar activity selection modal
    $('.open_add_calendar_activity-d').on('click', function(e) {
        $('#add_calendar_activity_modal-d').modal('show');
    });

    // select calendar activity type
    $('#add_calendar_activity_modal-d').on('click', '.calendar_activity_card-d', function(e) {
        let elm = $(this);
        let activity_type = $(elm).attr('data-activity_type');
        $('.calendar_activity_card-d').removeClass('active');
        $(elm).addClass('active');

        $('#add_calendar_activity_modal-d').find('.hdn_activity_type_selection-d').val(activity_type).attr('value', activity_type);
    });

    // open up relavent popup for quiz
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

    // trigger upload assignment media wizard
    $('#trigger_assignment_media_upload-d').on('click', function(e) {
        $('#upload_assignment_media-d').trigger('click');
    });

    // add|update courses drop down based on course selection
    $('#frm_add_assignment-d').on('change', '#ddl_course_uuid-d', function(e) {
        let elm = $(this);
        let selection = $(elm).val();
        let data = { 'course_uuid': selection };

        $.ajax({
            url: modal_get_slots_by_course,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                if (response.status) {
                    let model = response.data;
                    let slots = model.slots;
                    let ddlSlots = $('#ddl_course_slot-d');
                    $('#ddl_course_slot-d').html('');
                    $.each(slots, function(index, elm) {
                        let start = elm.model_start_date + ' : ' + elm.model_start_time;
                        let end = elm.model_end_date + ' : ' + elm.model_end_time;
                        ddlSlots.append("<option value='" + elm.uuid + "'>" + start + ' - ' + end + "</option>");
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        // location.reload();
                        // $('#frm_donate-d').trigger('reset');
                    });
                }
            },
            error: function(xhr, message, code) {
                Swal.fire({
                    title: 'Error',
                    text: 'Something went Wrong',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    // location.reload();
                    // $('#frm_donate-d').trigger('reset');
                });
                // console.log(xhr, message, code);
                hidePreLoader();
            },
            complete: function() {
                hidePreLoader();
            },
        });

    });

    $('#frm_add_assignment-d').validate({
        rules: {
            course_uuid: {
                required: true,
            },
            course_slot_uuid: {
                required: true,
            },
            start_date: {
                required: true,
                date: true,
            },
            media_1: {
                required: true,
                string: true,
                minLength: 1,
            },
            due_date: {
                required: true,
                date: true,
                greaterThanOrEqual: "#assignment_start_date-d"
            },
            total_marks: {
                required: true,
                number: true,
                min: 0,
                max: 100,
            },
        },
        messages: {
            course_uuid: {
                required: "Course is Required",
            },
            course_slot_uuid: {
                required: "Course Slot is Required",
            },
            media_1: {
                required: "Media File is Required",
            },
            total_marks: {
                required: "Total Marks field is Required",
                min: "Marks Must be a Nono-Negetive Number",
                max: 'Marks value cannot exceed 100'
            },
            start_date: {
                required: "Start Date is Required.",
            },
            due_date: {
                required: "Due Date is Required.",
                greaterThanOrEqual: 'Due date Must be greater or Equal to the start date',
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
            //console.log('submit handler');
            var current_form = $(form).serialize();
            var base_form = $('#frm_course_details-d').serialize();
            var form_data = base_form + "&" + current_form;

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: form_data,
                beforeSend: function() {
                    showPreLoader();
                },
                success: function(response) {
                    if (response.status) {

                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            $(form).parents('.modal').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            // location.reload();
                            // $('#frm_donate-d').trigger('reset');
                        });
                    }
                },
                error: function(xhr, message, code) {
                    // console.log(xhr, message, code);
                    let msg = 'Something went wrong'
                    if (xhr.responseJSON) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Error',
                        text: msg,
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        // location.reload();
                        // $('#frm_donate-d').trigger('reset');
                    });
                    // console.log(xhr, message, code);
                    hidePreLoader();
                },
                complete: function() {
                    hidePreLoader();
                },
            });
            return false;
        }
    });
});
