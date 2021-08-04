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
            let modal = $('#modal_add_quiz-d');
            // $(modal).find('.btn_quiz_save-d').show();

            // $(modal).find('.ddl_course_uuid-d').val('').removeAttr('disabled');
            // $(modal).find('.ddl_course_slot-d').val('').removeAttr('disabled');
            // $(modal).find('.quiz_due_date-d').val('').removeAttr('disabled');
            // $(modal).find('.total_marks-d').val('').removeAttr('disabled');
            // $(modal).find('.quiz_title-d').val('').removeAttr('disabled');
            // $(modal).find('.hdn_quiz_uuid-d').val('').removeAttr('disabled');

            switchModal('add_calendar_activity_modal-d', 'modal_add_quiz-d');
        } else { // pop assignment modal
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

    // add|update courses drop down|view section based on course selection
    $('#frm_add_assignment-d #ddl_course_uuid-d, #frm_add_quiz-d .ddl_course_uuid-d').on('change', function(e) {
        let elm = $(this);
        let selection = $(elm).val();

        const data = { 'course_uuid': selection };
        data.show_view = ($(elm).hasClass('ddl_course_uuid-d')) ? true : false;

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
                    if ($(elm).hasClass('ddl_course_uuid-d')) {
                        $('#frm_add_quiz-d').find('.course_slots_activity_container-d').html(model.slots_view);
                        $('#frm_add_quiz-d').find('.hdn_slot_uuid-d').val('');
                    } else {
                        let slots = model.slots;
                        let ddlSlots = $('#ddl_course_slot-d');
                        $('#ddl_course_slot-d').html('');
                        $.each(slots, function(index, elm) {
                            let start = elm.model_start_date + ' : ' + elm.model_start_time;
                            let end = elm.model_end_date + ' : ' + elm.model_end_time;
                            ddlSlots.append("<option value='" + elm.uuid + "'>" + start + ' - ' + end + "</option>");
                        });
                    }
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

    // add|update Assignment
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
                            location.reload();

                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            location.reload();
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

    // mark a slot as selected
    $('#frm_add_quiz-d').on('click', '.slot_option-d', function(e) {
        let elm = $(this);
        let slot_uuid = $(elm).attr('data-slot_option_uuid');
        let form = $(elm).parents('form');
        $(form).find('.slot_option-d').removeClass('bg_success-s').addClass('bg_light_dark-s');
        $(elm).removeClass('bg_light_dark-s').addClass('bg_success-s');
        $(form).find('.hdn_slot_uuid-d').val(slot_uuid);
    });

    // add|update Quiz from activity Calendar
    $('#frm_add_quiz-d').validate({
        rules: {
            quiz_title: {
                required: true,
            },
            quiz_type: {
                required: true,
            },
            quiz_duration: {
                required: true,
                min: 30,
                max: 180
            },
            due_date: {
                required: true,
                date: true,
            },
            course_uuid: {
                required: true,
            },
            description: {
                required: true,
            },
            slot_uuid: {
                required: true,
            },
            // quiz_uuid: {
            //     required: true,
            // },
        },
        messages: {
            quiz_title: {
                required: "Quiz title is Required",
            },
            quiz_type: {
                required: "Quiz Type is Required",
            },
            quiz_duration: {
                required: "Quiz Duration is required Required",
                min: "Minimun quiz duration can not less than 30 minutes",
                max: "Max quiz duration can not exceed 180 minutes",
            },
            due_date: {
                required: "Due Date is Required.",
                date: "Due Date is a Data.",
            },
            course_uuid: {
                required: "Course is Required",
            },
            description: {
                required: "Description is Required.",
            },
            slot_uuid: {
                required: "Slot is Required.",
            },
            // quiz_uuid: {
            //     required: "Quiz is Required.",
            // },
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
            var form_data = $(form).serialize();

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
                        location.reload();
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
        let elm = $(this);
        let is_self_process_quiz = ($(elm).hasClass('self_processing_quiz-d')) ? true : false;
        // console.log($(elm).hasClass('self_processing_quiz-d'));
        let modal = $('#check_test_modal-d');
        let data = {
            course_uuid: $(modal).find('.course_uuid-d').val(),
            quiz_uuid: $(modal).find('.quiz_uuid-d').val(),
            student_uuid: $(modal).find('.student_uuid-d').val()
        };
        $.ajax({
            url: (is_self_process_quiz == true) ? get_test_quiz_result_data : load_test_quiz_data,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                if (response.status) {
                    if (is_self_process_quiz == true) {
                        // get_test_quiz_result_data
                        console.log(response);
                    } else {
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
                    }
                    switchModal('check_test_modal-d', 'mark_test_quiz_answers_modal-d');

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
    // console.log(calendar_events_data);
    // fullcalendar
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
            // console.log(info);
            // console.log(info.extendedProps.quiz_type);

            let extendedProps = info.extendedProps;
            if (info.isStudent) { // student side
                if (extendedProps.nature == 'quiz') { // quiz
                    // console.log('welcome to stuent quiz');
                    $.ajax({
                        url: info.extendedProps.url,
                        type: 'POST',
                        dataType: 'json',
                        data: {},
                        beforeSend: function() {
                            showPreLoader();
                        },
                        success: function(response) {
                            let model = response.data;
                            if (extendedProps.is_quiz_attempted) { // case: I have attempted this Quiz
                                let modal = $('#mcqs_result-d');
                                $(".quiz_result_course_tilte-d").text(extendedProps.ref_model.course.title);
                                $(".quiz_result_title-d").text(extendedProps.ref_model.title);
                                // $(".quiz_result_title-d").text(extendedProps.ref_model.title);
                                $(".quiz_result_type-d").text(extendedProps.ref_model.type);
                                $(".quiz_result_description-d").text(extendedProps.ref_model.description);
                                $(".quiz_result_total_marks-d").text(extendedProps.ref_model.total_marks);
                                let attempt = extendedProps.student_quiz_attempt;
                                $(".quiz_result_test_date-d").text(extendedProps.ref_model.due_date);
                                let obtained_marks = 0;
                                if ('marked' == attempt.status) { // case: quiz is marked
                                    obtained_marks = attempt.total_correct_answers * attempt.marks_per_question;
                                    $(".quiz_result_status-d").text('Completed');
                                } else {
                                    obtained_marks = 0;
                                    $(".quiz_result_status-d").text('Teacher has not marked yet');
                                }
                                $(".quiz_result_obtained_marks-d").text(obtained_marks);
                                $(modal).modal('show');

                            } else { // case: I have not attempted the quiz yet
                                let modal = $('#start_mcqs-d');
                                $('.quiz_type-d').text(info.extendedProps.quiz_type);
                                $('.quiz_course_title-d').text(extendedProps.ref_model.course.title);
                                $('.quiz_title-d').text(extendedProps.ref_model.title);

                                $('.quiz_description-d').text(extendedProps.ref_model.description);
                                $('.quiz_duration-d').text(extendedProps.ref_model.duration_mins);
                                $('.quiz_due_date-d').text(extendedProps.ref_model.due_date);
                                if (extendedProps.ref_model.can_attempt) {
                                    $('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url);
                                    $('.btn_view_quiz_link-d').text('START');
                                    $('.expired_quiz_text-d').addClass('d-none');
                                    $('.btn_view_quiz_link-d').parent().removeAttr('disabled');
                                } else {
                                    $('.btn_view_quiz_link-d').attr('href', 'javascript:void(0)');
                                    $('.btn_view_quiz_link-d').text('Expired');
                                    $('.btn_view_quiz_link-d').parent().attr('disabled', 'disabled');
                                    $('.expired_quiz_text-d').removeClass('d-none');
                                }
                                $(modal).modal('show');
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
                } else { // assignment
                    console.log(info);
                    let extendedProps = info.extendedProps;
                    let model = extendedProps.ref_model;

                    if (extendedProps.is_assignment_attempted) { // case: I have attempted Assignment
                        let modal = $('#assignment_result-d');
                        $(modal).find('.assignment_title-d').text(extendedProps.ref_model.title);
                        $(modal).find('.due_date_assignment-d').text(extendedProps.ref_model.due_date);
                        $(modal).find(".total_marks-d").text(extendedProps.ref_model.total_marks);

                        let attempt = extendedProps.ref_model.my_attempt;
                        let obtained_marks = 'Teacher has not Marked your Assignment Yet';
                        if (attempt.status == 'marked') {
                            obtained_marks = attempt.obtained_marks;
                        }
                        $(modal).find(".obtain_marks-d").text(obtained_marks);
                        $(modal).modal('show');
                    } else { // case: I have not attempted Assignment yer
                        let modal = $('#assignment-d');
                        let file = model.media_1;
                        let file_name = file.substring(11);
                        // // console.log(file, file_name);
                        $(modal).find('.course_uuid-d').text(model.course.uuid);
                        $(modal).find('.assignment_uuid-d').text(model.uuid);
                        $(modal).find('.assignment_title-d').text(model.title);
                        $(modal).find('.submit_assignment_title-d').text(model.title);
                        $(modal).find('.assignmet_file-d').attr('src', model.model_media_1);
                        $(modal).find('.assignment_due_date-d').text(model.due_date);
                        $(modal).find('.submit_assignment_due_date-d').text(model.due_date);
                        $(modal).find('.download_assignmet_file-d').attr('href', 'uploads/' + file_name);
                        if (extendedProps.ref_model.can_attempt) {
                            $(modal).find('.submit_assignment-d').removeClass('d-none');
                            $(modal).find('.assignment_popup_text-d').text('Download your Assignment File');
                            // $('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url);
                            // $('.btn_view_quiz_link-d').text('START');
                            // $('.expired_quiz_text-d').addClass('d-none');
                            // $('.btn_view_quiz_link-d').parent().removeAttr('disabled');
                        } else {
                            $(modal).find('.submit_assignment-d').addClass('d-none');
                            $(modal).find('.assignment_popup_text-d').text('Assignment date has passed. You cannot attempt this Assignment anymore.');
                            // $('.btn_view_quiz_link-d').attr('href', 'javascript:void(0)');
                            // $('.btn_view_quiz_link-d').text('Expired');
                            // $('.btn_view_quiz_link-d').parent().attr('disabled', 'disabled');
                            // $('.expired_quiz_text-d').removeClass('d-none');
                        }

                        // $('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url);
                        $(modal).modal('show');
                    }
                }
            } else { // teacher side

            }

            console.log('==========================================================');
            return false;
            // console.log(info.extendedProps, info.extendedProps.nature);
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
                            // console.log(response)
                            // console.log(info.isStudent);
                            // console.log(response.data.quiz.is_attempted_quiz);

                            // return false;
                            if (response.status) {
                                if (info.isStudent) {
                                    let model = response.data;
                                    if ((info.extendedProps.is_attempted == true) && ('quiz_attempt_stats' == info.extendedProps.ref_model_name)) {
                                        // $(".quiz_result_course_tilte-d").text(model.student_attempt.quiz.course.title);
                                        // $(".quiz_result_title-d").text(model.student_attempt.quiz.title);
                                        // // $(".quiz_result_title-d").text(model.student_attempt.quiz.title);
                                        // $(".quiz_result_type-d").text(model.student_attempt.quiz.type);
                                        // $(".quiz_result_description-d").text(model.quiz.student_attempt.description);
                                        // $(".quiz_result_total_marks-d").text(model.quiz.student_attempt.total_marks);
                                        // let result = model.student_attempt.quiz.student_quiz_answers;
                                        // $.each(result, function(i, e) {
                                        //     console.log(e.status);
                                        //     if (e.status == 'pending') {
                                        //         $(".text-d").text('Teacher has not marked yet');
                                        //     } else {
                                        //         $(".text-d").text('completed');
                                        //     }
                                        // })
                                        // let obtained_marks = model.quiz.my_attempt.total_correct_answers * model.quiz.my_attempt.marks_per_question;
                                        // console.log(obtained_marks);
                                        // $(".quiz_result_obtained_marks-d").text(obtained_marks);
                                        // $(".quiz_result_test_date-d").text(model.quiz.due_date);
                                        $('#mcqs_result-d').modal('show');
                                    } else {
                                        // let modal = $('#start_mcqs-d');
                                        // $('.quiz_type-d').text(info.extendedProps.quiz_type);
                                        // $('.quiz_course_title-d').text(model.quiz.course.title);
                                        // $('.quiz_title-d').text(model.quiz.title);
                                        // $('.quiz_description-d').text(model.quiz.description);
                                        // $('.quiz_duration-d').text(model.quiz.duration_mins);
                                        // $('.quiz_due_date-d').text(model.quiz.due_date);
                                        // $('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url);
                                        // $('#start_mcqs-d').modal('show');
                                    }
                                } else {
                                    // let model = response.data;
                                    // let modal = $('#check_test_modal-d');
                                    // $(modal).find('.btn_see_test-d').removeClass('self_processing_quiz-d');
                                    // if (model.sender_id == current_user_profile_id) {
                                    //     $('.modal_heading-d').text('View Quiz');
                                    //     $(modal).find('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url).show();
                                    //     $(modal).find('.btn_see_test-d').hide();

                                    //     $(modal).find('.modal_profile_name-d').text(model.sender.first_name + ' ' + model.sender.last_name);
                                    //     $(modal).find('.modal_profile_image-d').attr('src', model.sender.profile_image);
                                    // $(modal).find('.student_uuid-d').val(model.sender.uuid).attr('value', model.sender.uuid);

                                    // } else {
                                    //     $('.modal_heading-d').text('Check Test');
                                    //     $(modal).find('.btn_see_test-d').removeAttr('disabled');
                                    //     $(modal).find('.btn_view_quiz_link-d').hide();

                                    //     // $(modal).find('.modal_profile_name-d').text(model.sender.first_name + ' ' + model.sender.last_name);
                                    //     // $(modal).find('.modal_profile_image-d').attr('src', model.sender.profile_image);
                                    //     // $(modal).find('.student_uuid-d').val(model.sender.uuid).attr('value', model.sender.uuid);
                                    // }

                                    // $(modal).find('.modal_course_title-d').text(model.quiz.course.title);
                                    // $(modal).find('.modal_course_category-d').text(model.quiz.course.category.name);

                                    // $(modal).find('.course_uuid-d').val(model.quiz.course.uuid).attr('value', model.quiz.course.uuid);
                                    // $(modal).find('.quiz_uuid-d').val(model.quiz.uuid).attr('value', model.quiz.uuid);

                                    $('#check_test_modal-d').modal('show');
                                }

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
                                // console.log(info);
                                if (info.isStudent) {
                                    let model = response.data;
                                    if (info.extendedProps.is_attempted == false) { // case its not attempted yet
                                        // let modal = $('#start_mcqs-d');
                                        // $('.quiz_type-d').text(info.extendedProps.quiz_type);
                                        // $('.quiz_course_title-d').text(model.quiz.course.title);
                                        // $('.quiz_title-d').text(model.quiz.title);
                                        // $('.quiz_description-d').text(model.quiz.description);
                                        // $('.quiz_duration-d').text(model.quiz.duration_mins);
                                        // $('.quiz_due_date-d').text(model.quiz.due_date);
                                        // $('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url);
                                        $('#start_mcqs-d').modal('show');
                                    } else { // case its already attempted

                                        // $('.quiz_type-d').text(info.extendedProps.quiz_type);
                                        // $('.quiz_result_course_tilte-d').text(model.quiz.course.title);
                                        // $('.quiz_result_title-d').text(model.quiz.course.title);
                                        // $('.quiz_result_description-d').text(model.quiz.description);
                                        // $('.quiz_result_total_marks-d').text(model.quiz.total_marks);

                                        // if (model.quiz.my_attempt.status == 'marked') {
                                        //     $('.quiz_result_total_marks-d').text(model.quiz.total_marks);
                                        //     $('.quiz_result_status-d').text(model.quiz.my_attempt.status);
                                        //     console.log(model.quiz.my_attempt);
                                        //     $('.quiz_result_obtained_marks-d').text(model.quiz.my_attempt.marks_per_question * model.quiz.my_attempt.total_correct_answers);
                                        // } else {
                                        //     $('.quiz_result_obtained_marks-d').text(0);
                                        //     $('.quiz_result_total_marks-d').text(0);
                                        //     $('.quiz_result_status-d').text('Teacher has not marked yet');
                                        // }
                                        console.log(model);
                                        // let modal = $('#start_mcqs-d');
                                        // $('.quiz_due_date-d').text(model.quiz.due_date);
                                        // $('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url);
                                        $('#mcqs_result-d').modal('show');
                                    }
                                } else { // teacher side
                                    console.log('teacher side');
                                    let model = response.data;
                                    let modal = $('#check_test_modal-d');
                                    if (info.extendedProps.ref_model_name == 'quiz_attempt_stats') {
                                        console.log(response);
                                        // $(modal).find('.btn_see_test-d').addClass('self_processing_quiz-d');
                                        // if (model.sender_id == current_user_profile_id) {
                                        //     $('.modal_heading-d').text('View Quiz');
                                        //     $(modal).find('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url).show();
                                        //     $(modal).find('.btn_see_test-d').hide();

                                        //     $(modal).find('.modal_profile_name-d').text(model.sender.first_name + ' ' + model.sender.last_name);
                                        //     $(modal).find('.modal_profile_image-d').attr('src', model.sender.profile_image);
                                        //     // $(modal).find('.student_uuid-d').val(model.sender.uuid).attr('value', model.sender.uuid);
                                        // } else {
                                        //     $('.modal_heading-d').text('Check Test');
                                        //     $(modal).find('.btn_see_test-d').removeAttr('disabled');
                                        //     $(modal).find('.btn_view_quiz_link-d').hide();

                                        //     // $(modal).find('.modal_profile_name-d').text(model.sender.first_name + ' ' + model.sender.last_name);
                                        //     // $(modal).find('.modal_profile_image-d').attr('src', model.sender.profile_image);
                                        //     // $(modal).find('.student_uuid-d').val(model.sender.uuid).attr('value', model.sender.uuid);
                                        // }
                                        $(modal).modal('show');

                                    } else {
                                        // if (info.extendedProps.is_attempted == false) {
                                        // if (info.extendedProps.ref_model_name == 'quiz_attempt_stats') {

                                        //     $(modal).find('.modal_course_title-d').text(model.student_attempt.course.title);
                                        //     $(modal).find('.modal_course_category-d').text(model.student_attempt.course.category.name);

                                        //     $(modal).find('.course_uuid-d').val(model.student_attempt.course.uuid).attr('value', model.student_attempt.course.uuid);
                                        //     $(modal).find('.quiz_uuid-d').val(model.student_attempt.quiz.uuid).attr('value', model.student_attempt.quiz.uuid);
                                        // } else {
                                        //     $(modal).find('.modal_course_title-d').text(model.quiz.course.title);
                                        //     $(modal).find('.modal_course_category-d').text(model.quiz.course.category.name);

                                        //     $(modal).find('.course_uuid-d').val(model.quiz.course.uuid).attr('value', model.quiz.course.uuid);
                                        //     $(modal).find('.quiz_uuid-d').val(model.quiz.uuid).attr('value', model.quiz.uuid);
                                        // }
                                        console.log('quiz is attempted');
                                    }
                                }
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
                }

            } else { // assignment
                if (info.extendedProps.nature == 'assignment') {
                    // its an assignment
                    $.ajax({
                        url: info.extendedProps.url,
                        type: 'POST',
                        dataType: 'json',
                        data: {},
                        beforeSend: function() {
                            showPreLoader();
                        },
                        success: function(response) {
                            // console.log(response);
                            // console.log(response.data.assignment.course.enrolled_students);
                            // console.log(response.data.assignment.slot_id);
                            // // return false;
                            // let slot_id = response.data.assignment.slot_id;

                            let enrollment_course_slot = response.data.assignment.course.enrolled_students;
                            let enroll_slot_id = null;
                            $.each(enrollment_course_slot, function(index, elm) {
                                enroll_slot_id = elm.slot_id;
                            });
                            console.log(enroll_slot_id);

                            if (response.status) {
                                if ((info.isStudent)) {
                                    let model = response.data;

                                    if (model.assignment) {

                                        if (!model.assignment.is_uploaded_assignment) {
                                            let file = model.assignment.media_1;
                                            let file_name = file.substring(11);
                                            // console.log(file, file_name);

                                            $('.course_uuid-d').text(model.assignment.course.uuid);
                                            $('.assignment_uuid-d').text(model.assignment.uuid);
                                            $('.assignment_title-d').text(model.assignment.title);
                                            $('.submit_assignment_title-d').text(model.assignment.title);
                                            $('.assignmet_file-d').text(model.assignment.media_1);
                                            $('.assignment_due_date-d').text(model.assignment.due_date);
                                            $('.submit_assignment_due_date-d').text(model.assignment.due_date);
                                            $('.download_assignmet_file-d').attr('href', 'uploads/' + file_name);
                                            // $('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url);
                                            $('#assignment-d').modal('show');

                                        }
                                    } else {
                                        let total_marks = model.student_assignment.teacher_assignment.total_marks;

                                        if (model.student_assignment.teacher_assignment.is_uploaded_assignment) {
                                            // check if status is marked or pending
                                            // console.log(model.sender.first_name);
                                            $('.assignment_title-d').text(model.student_assignment.teacher_assignment.title);
                                            $('.assignment_due_date-d').text(model.student_assignment.teacher_assignment.due_date);
                                            $(".total_marks-d").text(total_marks);


                                            if (model.student_assignment.status == 'pending') {
                                                // console.log('pending');
                                                // $(".total_marks-d").text(total_marks);
                                                $(".obtain_marks-d").text('pending');
                                                $('#assignment_result-d').modal('show');
                                            } else {
                                                // console.log('marked');
                                                $(".obtain_marks-d").text(model.student_assignment.obtained_marks);
                                                $('#assignment_result-d').modal('show');
                                            }
                                            // return false;
                                        }
                                    }
                                    // else
                                    // {
                                    //     let file = model.assignment.media_1;
                                    //     let file_name = file.substring(11);
                                    //     // console.log(file_name);

                                    //     $('.course_uuid-d').text(model.assignment.course.uuid);
                                    //     $('.assignment_uuid-d').text(model.assignment.uuid);
                                    //     $('.assignment_title-d').text(model.assignment.title);
                                    //     $('.submit_assignment_title-d').text(model.assignment.title);
                                    //     $('.assignmet_file-d').text( model.assignment.media_1);
                                    //     $('.assignment_due_date-d').text(model.assignment.due_date);
                                    //     $('.submit_assignment_due_date-d').text(model.assignment.due_date);
                                    //     $('.download_assignmet_file-d').attr('href','uploads/'+ file_name);
                                    //     // $('.btn_view_quiz_link-d').attr('href', info.extendedProps.ref_model_url);
                                    //     $('#assignment-d').modal('show');
                                    // }

                                } else {
                                    // console.log(info.extendedProps);
                                    let model = response.data;
                                    // console.log(model);
                                    // console.log(model.noti_type);

                                    // if teacher already marked an assignment
                                    if (info.extendedProps.has_teacher_marked_assignment) {
                                        $(".checked_assignment_title-d").text(model.student_assignment.teacher_assignment.title);
                                        let date = model.student_assignment.updated_at.split('T');
                                        // console.log(date.split('T'));

                                        let updated_date = date[0];
                                        // console.log(updated_date);

                                        $(".checked_assignment_date-d").text(updated_date);
                                        $(".checked_assignment_total_marks-d").text(model.student_assignment.teacher_assignment.total_marks);
                                        $(".checked_assignment_obtained_marks-d").text(model.student_assignment.obtained_marks);
                                        $("#new_assignment_result-d").modal('show');
                                    } else if (model.noti_type == 'upload_assignment') { // if student uploaded assignment , then show following modal
                                        // console.log(model);
                                        let file = model.student_assignment.media;
                                        let file_name = file.substring(11);
                                        console.log(model.student_assignment.teacher_assignment.due_date);


                                        $('.course_uuid-d').text(model.student_assignment.course.uuid);
                                        $('.student_assignment_uuid-d').text(model.student_assignment.uuid);
                                        $('.student_assignment_title-d').text(model.student_assignment.teacher_assignment.title);
                                        $('.submit_assignment_title-d').text(model.student_assignment.teacher_assignment.title);
                                        $('.total_assignment_marks-d').text(model.student_assignment.teacher_assignment.total_marks);
                                        $('.assignmet_file-d').text(file);
                                        $('.student_assignment_due_date-d').text(model.student_assignment.teacher_assignment.due_date);
                                        $('.submit_assignment_due_date-d').text(model.student_assignment.teacher_assignment.due_date);
                                        $('.download_assignmet_file-d').attr('href', 'uploads/' + file_name);

                                        $('.teacher_name-d').text(model.receiver.first_name);
                                        $('.student_name-d').text(model.sender.first_name);


                                        $("#student_assignment-d").modal('show');
                                    } else {

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
                                    }

                                }
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
                } else { // course slot
                    // its an assignment
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
                                console.log(response);

                                console.log(model.is_lecture_time);

                                if (model.enrolments_count < 1) {
                                    errorAlert('This Slot Does not have any Enrollment');
                                    return false;
                                }
                                if (info.isStudent) {
                                    // let model = response.data;
                                    $('.course_title-d').text(model.course.title);
                                    $('.class_start_date-d').text(model.model_start_date);
                                    $('.class_start_time-d').text(model.model_start_time);
                                    if (model.is_lecture_time) {
                                        $(".class_schedule_start-d").removeAttr('disabled', true)
                                    }
                                    $("#class_schedule-d").modal('show');

                                } else {
                                    let modal = $('#lecture_modal-d');
                                    // .
                                    $(modal).find('.slot_sr-d').text(model.uuid);
                                    $(modal).find('.time_left-d').text(model.time_left);
                                    $(modal).find('.slot_student_name-d').attr('data-student_uuid', model.last_enrolment.student.uuid).text(model.last_enrolment.student.first_name + ' ' + model.last_enrolment.student.last_name);
                                    //course_slot_uuid
                                    $(modal).find('.hdn_course_slot_uuid-d').val(model.uuid);

                                    $(modal).find('.slot_start-d').text(model.model_start_time);
                                    $(modal).find('.slot_end-d').text(model.model_end_time);
                                    $(modal).find('.slot_course_title-d').text(model.course.title);
                                    $(modal).find('.slot_course_type-d').text(model.course.is_course_free ? 'Free' : 'paid');

                                    if (model.is_lecture_time) {
                                        $(".btn_show_zoom_meeting_modal-d").removeAttr('disabled', true)
                                    }

                                    $(modal).modal('show');

                                }

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

    // add|update Quiz from activity Calendar
    $('#frm_send_invite_link-d').validate({
        rules: {
            course_uuid: {
                required: true,
            },
            slot_uuid: {
                required: true,
            },
            zoom_meeting_url: {
                required: true,
            },
        },
        messages: {
            zoom_meeting_url: {
                required: "Zoom Meeting URL is Required",
            },
            course_uuid: {
                required: "Course is Required",
            },
            slot_uuid: {
                required: "Slot is Required.",
            },
            // quiz_uuid: {
            //     required: "Quiz is Required.",
            // },
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
            var form_data = $(form).serialize();
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

    $('#lecture_modal_slot-d').on('click', function(e) {
        let elm = $(this);
        // let currentModal = $(elm).parent('modal');
        // let slot_uuid = $
        let slot_uuid = $(elm).find('.hdn_course_slot_uuid-d').val();
        console.log(slot_uuid);

        // let targetModal = $('#modal_send_zoom_meeting_link-d');
        // let slot_uuid = $(targetModal).find('.hdn_course_slot_uuid-d').val();
        $(".hdn_get_course_slot_uuid-d").val(slot_uuid);
        // switchModal('lecture_modal-d', 'modal_send_zoom_meeting_link-d');
        $("#lecture_modal-d").modal('hide');
        $("#modal_send_zoom_meeting_link-d").modal('show');

    });


    // student upload modal switch
    $(".submit_assignment-d").on('click', function(e) {
        let elm = $(this);
        let course_uuid = $(elm).find('.course_uuid-d').text();
        let assignment_uuid = $(elm).find('.assignment_uuid-d').text();
        let due_date_assignmnet = $(elm).find('.submit_assignment_due_date-d').text();
        let assignment_title = $(elm).find('.submit_assignment_title-d').text();
        // console.log(elm);
        // console.log(course_uuid);
        // console.log(assignment_uuid);
        // console.log(due_date_assignmnet);
        // console.log(assignment_uuid);
        $('.get_course_uuid-d').val(course_uuid);
        $('.get_assignment_uuid-d').val(assignment_uuid);
        $('.due_date_assignment-d').text(due_date_assignmnet);
        $('.assignment_title-d').text(assignment_title);

        //     currentModal.hide();
        switchModal('assignment-d', 'assignment_submit-d');

    });


    // student upload assignment
    $('#student_submit_assignment-d').validate({
        ignore: ".ignore",
        rules: {
            upload_assignment_image: {
                required: true,
            },
        },
        messages: {
            upload_assignment_image: {
                required: "Please upload your assignment",
            }
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                beforeSend: function() {
                    showPreLoader();
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        location.reload();

                    });
                },
                error: function(xhr, message, code) {
                    response = xhr.responseJSON;
                    if (404 == response.exceptionCode) {
                        let container = $('#txt_forgot_pass_email-d').parent();
                        if ($(container).find('.error').length > 0) {
                            $(container).find('.error').remove();
                        }
                        $(container).append("<span class='error'>" + response.message + "</span>");
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            // $('#frm_donate-d').trigger('reset');
                        });
                    }
                    // console.log(xhr, message, code);
                    hidePreLoader();
                },
                complete: function() {
                    hidePreLoader();
                },
            });
            return false;
            // add Question
        }
    });



    // teacher mark down student modal switch
    $(".mark_assignment-d").on('click', function(e) {
        let elm = $(this);
        let course_uuid = $(elm).find('.course_uuid-d').text();
        let teacher_name = $(elm).find('.teacher_name-d').text();
        let student_name = $(elm).find('.student_name-d').text();
        let student_assignment_uuid = $(elm).find('.student_assignment_uuid-d').text();
        let due_date_assignmnet = $(elm).find('.submit_assignment_due_date-d').text();
        let student_assignment_title = $(elm).find('.submit_assignment_title-d').text();
        let total_assignment_marks = $(elm).find('.total_assignment_marks-d').text();

        $('.get_course_uuid-d').val(course_uuid);
        $('.get_student_assignment_uuid-d').val(student_assignment_uuid);
        $('.mark_student_name-d').text(student_name);
        $('.mark_teacher_name-d').text(teacher_name);
        $('.due_date_assignment-d').text(due_date_assignmnet);
        $('.student_assignment_title-d').text(student_assignment_title);
        $('.total_marks-d').text(total_assignment_marks);

        //     currentModal.hide();
        switchModal('student_assignment-d', 'mark_student_assignment-d');

    });


    //teacher mark assignment
    $('#frm_mark_student_assignment-d').validate({
        ignore: ".ignore",
        rules: {
            obtained_marks: {
                required: true,
                min: 0,
            },
        },
        messages: {
            obtained_marks: {
                required: "Please mark the assignment",
                min: "marks can not be less than 0"
            }
        },
        errorPlacement: function(error, element) {
            $('#' + error.attr('id')).remove();
            error.insertAfter(element);
            $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
        },
        success: function(label, element) {
            $(element).removeClass('error');
            $(element).parent().find('span.error').remove();
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                beforeSend: function() {
                    showPreLoader();
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        location.reload();

                    });
                },
                error: function(xhr, message, code) {
                    response = xhr.responseJSON;
                    if (404 == response.exceptionCode) {
                        let container = $('#txt_forgot_pass_email-d').parent();
                        if ($(container).find('.error').length > 0) {
                            $(container).find('.error').remove();
                        }
                        $(container).append("<span class='error'>" + response.message + "</span>");
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            // $('#frm_donate-d').trigger('reset');
                        });
                    }
                    // console.log(xhr, message, code);
                    hidePreLoader();
                },
                complete: function() {
                    hidePreLoader();
                },
            });
            return false;
            // add Question
        }
    });

});