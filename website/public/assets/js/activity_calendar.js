$(function(event) {
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
            let modal = $('#modal_add_assignment-d');
            $(modal).find('.btn_assignment_save-d').show();
            $(modal).find('#ddl_course_uuid-d').val('').removeAttr('disabled');
            $(modal).find('#ddl_course_slot-d').val('').removeAttr('disabled');

            $(modal).find('#assignment_start_date-d').val('').removeAttr('disabled');
            $(modal).find('#assignment_due_date-d').val('').removeAttr('disabled');

            $(modal).find('#total_marks-d').val('').removeAttr('disabled');
            $(modal).find('#assignment_title-d').val('').removeAttr('disabled');
            $(modal).find('.hdn_assignment_uuid-d').val('').removeAttr('disabled');
            $(modal).find('.hdn_assignment_media_1-d').val('').removeAttr('disabled');

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

    // Addan Assignment
    $('#frm_add_assignment-d').validate({
        rules: {
            course_uuid: {
                required: true,
            },
            assignment_title: {
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
            title: {
                required: "Title is Required.",
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

    // open see test modal popup
    $('#check_test_modal-d').on('click', '.btn_see_test-d', function(e) {
        // make an ajax call and fetch all the answers given againts a test quiz
        // load_test_quiz_data

        // <input type="hidden" name='course_uuid' class='course_uuid-d' />
        // <input type="hidden" name='quiz_uuid' class='quiz_uuid-d' />
        // <input type="hidden" name='student_uuid' class='student_uuid-d' />
        let modal = $('#check_test_modal-d');
        let data = {
            course_uuid: $(modal).find('.course_uuid-d').val(),
            quiz_uuid: $(modal).find('.quiz_uuid-d').val(),
            student_uuid: $(modal).find('.student_uuid-d').val()
        };
        $.ajax({
            url: load_test_quiz_data,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                if (response.status) {
                    let models = response.data.answers;
                    // console.log(models);
                    let markingModal = $('#mark_test_quiz_answers_modal-d');
                    $(markingModal).find('.student_answers_main_container-d').html('');
                    $.each(models, function(index, model) {
                        // console.log(model);
                        let clonedElm = $('#cloneable_answer_container-d');
                        clonedElm.removeAttr('id').addClass('uuid_' + model.uuid);

                        $(clonedElm).find('.student_profile_image-d').attr('src', model.student.profile_image);
                        $(clonedElm).find('.student_name-d').text(model.student.first_name + ' ' + model.student.last_name);
                        $(clonedElm).find('.student_uuid-d').val(model.student.uuid);

                        $(clonedElm).find('.student_answer-d').text(model.answer_body);
                        $(clonedElm).find('.student_answer_uuid-d').val(model.uuid);

                        $(clonedElm).find('.asked_question-d').text(model.question.body);
                        $(clonedElm).find('.question_uuid-d').val(model.question.uuid);
                        $(clonedElm).find('.course_uuid-d').val(model.course.uuid);
                        $(clonedElm).find('.quiz_uuid-d').val(model.quiz.uuid);

                        $(markingModal).find('.student_answers_main_container-d').append(clonedElm);
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
        switchModal('check_test_modal-d', 'mark_test_quiz_answers_modal-d');
    });

    //
    $('.full-calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        // put your options and callbacks here
        // defaultView: 'agendaWeek',
        allDaySlot: false,
        eventLimit: 2,
        eventLimitText: 'See More',
        events: JSON.parse(calendar_events_data),
        eventClick: function(info) {
            if (info.extendedProps.nature == 'quiz') {
                if (info.extendedProps.quiz_type == 'test') {
                    console.log(info, 'test')

                    $.ajax({
                        url: info.extendedProps.url,
                        type: 'POST',
                        dataType: 'json',
                        data: {},
                        beforeSend: function() {
                            showPreLoader();
                        },
                        success: function(response) {
                            if (response.status) {
                                let model = response.data;
                                let modal = $('#check_test_modal-d');
                                if (model.sender_id == current_user_profile_id) {
                                    $('.modal_heading-d').text('View Quiz');
                                    $(modal).find('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url).show();
                                    $(modal).find('.btn_see_test-d').hide();
                                } else {
                                    $('.modal_heading-d').text('Check Test');
                                    $(modal).find('.btn_see_test-d').removeAttr('disabled');
                                    $(modal).find('.btn_view_quiz_link-d').hide();
                                }

                                $(modal).find('.modal_profile_name-d').text(model.sender.first_name + ' ' + model.sender.last_name);
                                $(modal).find('.modal_profile_image-d').attr('src', model.sender.profile_image);
                                $(modal).find('.modal_course_title-d').text(model.quiz.course.title);
                                $(modal).find('.modal_course_category-d').text(model.quiz.course.category.name);

                                $(modal).find('.course_uuid-d').val(model.quiz.course.uuid).attr('value', model.quiz.course.uuid);
                                $(modal).find('.quiz_uuid-d').val(model.quiz.uuid).attr('value', model.quiz.uuid);
                                $(modal).find('.student_uuid-d').val(model.sender.uuid).attr('value', model.sender.uuid);

                                $('#check_test_modal-d').modal('show');

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
                } else {
                    // alert(info.extendedProps.url);
                    // fetch result for that student
                    // show results popup directly
                    // if(info.extendedProps.url){
                    // }
                }
            } else {
                if (info.extendedProps.nature == 'assignment') {
                    // its an assignment
                    console.log(info.extendedProps);
                    $.ajax({
                        url: info.extendedProps.url,
                        type: 'POST',
                        dataType: 'json',
                        data: {},
                        beforeSend: function() {
                            showPreLoader();
                        },
                        success: function(response) {
                            if (response.status) {
                                let model = response.data;
                                let modal = $('#modal_add_assignment-d');

                                // https://stackoverflow.com/questions/21518381/proper-way-to-wait-for-one-function-to-finish-before-continuing
                                (function(next) {
                                    $(modal).find('#ddl_course_uuid-d').val(model.assignment.course.uuid);
                                    $(modal).find('#ddl_course_uuid-d').trigger('change');

                                    next()
                                }(function() {
                                    $(modal).find('#ddl_course_uuid-d').val(model.assignment.course.uuid).attr('disabled', 'disabled');
                                    $(modal).find('#ddl_course_slot-d').val(model.assignment.slot.uuid).attr('disabled', 'disabled');

                                    $(modal).find('#assignment_start_date-d').val(model.assignment.start_date).attr('disabled', 'disabled');
                                    $(modal).find('#assignment_due_date-d').val(model.assignment.due_date).attr('disabled', 'disabled');

                                    $(modal).find('#total_marks-d').val(model.assignment.total_marks).attr('disabled', 'disabled');
                                    $(modal).find('#assignment_title-d').val(model.assignment.title).attr('disabled', 'disabled');
                                    $(modal).find('.hdn_assignment_uuid-d').val(model.assignment.uuid).attr('disabled', 'disabled');
                                    $(modal).find('.hdn_assignment_media_1-d').val(model.assignment.media_1).attr('disabled', 'disabled');

                                    $(modal).find('.btn_assignment_save-d').hide();
                                    $(modal).modal('show');
                                }))
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
                            }).then((result) => {});
                            hidePreLoader();
                        },
                        complete: function() {
                            hidePreLoader();
                        },
                    });
                } else {
                    // course slot
                }
            }
            // var eventObj = info.event;
        },
        // eventClick: function(calEvent, jsEvent, view) {
        //     $('#event_id').val(calEvent._id);
        //     $('#appointment_id').val(calEvent.id);
        //     $('#start_time').val(moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss'));
        //     $('#finish_time').val(moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss'));
        //     $('#editModal').modal();
        // }
    });
});
