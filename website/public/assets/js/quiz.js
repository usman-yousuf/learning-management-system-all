$(document).ready(function() {
    $('#add_quiz_type_modal-d').on('click', function(e) {
        console.log("ok");
        $('#quiz_type_modal').modal('show');
    });


    // add-quiz-type submit - START
    $('#add_quiz_type-d').validate({
        ignore: ".ignore",
        rules: {
            quiz_type: {
                required: true
            },
            quiz_title: {
                required: true,
                minlength: 3,
            },
            quiz_duration: {
                required: true,
            },
            course_uuid: {
                required: true,
            },
            slot_uuid: {
                required: true,
            },
            due_date: {
                required: true,
                date: true,
            },
            description: {
                required: true,
            },
        },
        messages: {
            quiz_type: {
                required: "Quiz type is Required"
            },
            quiz_title: {
                required: "Title is Required",
                minlength: "Title Should have atleast 3 characters",
            },
            quiz_duration: {
                required: "Quiz Duration is Required",
            },
            due_date: {
                required: "Due Date is Required",
            },
            course_uuid: {
                required: "Course is Required",
            },
            slot_uuid: {
                required: 'Slot is Required',
            },
            description: {
                required: "Description is Required",
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
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
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
                            let model = response.data;
                            console.log(model.description);
                            $('.quiz_type_modal-d').modal('hide');
                            let type = 'test';
                            if (model.type == 'boolean') {
                                type = "True False";
                            } else if (model.type == 'mcqs') {
                                type = 'mcqs';
                            }

                            if ($('#cloneable_quiz_container-d').length > 0) {
                                let clonedElm = $('#cloneable_quiz_container-d').clone();
                                $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                let linkElm = $(clonedElm).find('.link-d');
                                let link = $(linkElm).attr('href');
                                link = link.replace('______', model.uuid);
                                $(linkElm).attr('href', link);
                                $(clonedElm).find('.title-d').text(model.title);
                                $(clonedElm).find('.type-d').text(type);
                                $(clonedElm).find('.duration-d').text(model.duration_mins);
                                $(clonedElm).find('.description-d').text(model.description);
                                $(clonedElm).find('.students_count-d').text(model.description_count);
                                $('.quiz_main_container-d').append(clonedElm);
                            }
                            $('#add_quiz_type-d').trigger("reset");
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
                    response = xhr.responseJSON;

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



    //add test question submit - START
    $('#frm_test_question-d').validate({
        ignore: ".ignore",
        rules: {
            add_question_textarea: {
                required: true,
                minlength: 5
            },
            add_answer_textarea: {
                required: true,
                minlength: 5,
            },
        },
        messages: {
            add_question_textarea: {
                required: "Question is required ",
                minlength: "Title Should have atleast 5 characters",
            },
            add_answer_textarea: {
                required: "Answer is required",
                minlength: "Title Should have atleast 5 characters",
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
            // debugger;

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
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
                            console.log("test question");
                            let model = response.data;
                            let clonedElm;
                            if ($('.uuid_' + model.uuid).length > 0) {
                                clonedElm = $('.uuid_' + model.uuid);
                            } else {
                                if ($('#single_clonable_question_test-d').length > 0) {
                                    clonedElm = $('#single_clonable_question_test-d').clone();
                                    $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                }
                            }
                            // console.log(clonedElm);

                            $(clonedElm).find('.question_uuid-d').text(model.uuid);
                            $(clonedElm).find('.body-d').text(model.body);
                            $(clonedElm).find('.correct_answer-d').text(model.correct_answer);

                            if ($('.uuid_' + model.uuid).length < 1) {
                                $('.test_questions_main-d').prepend(clonedElm);
                                // console.log(appended);
                            }


                            resetTestQuestionForm(form);
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
                    response = xhr.responseJSON;

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

    //delete test question
    $(".test_questions_main-d").on('click', '.delete_test_question-d', function(e) {
        let form = '';

        let elm = $(this);
        form = $('#frm_test_questions-d');

        let container = $(elm).parents('.single_test_question-d');
        let uuid = $(container).find('.question_uuid-d').val();

        console.log(uuid);
        var removeSlot = function() {
            $(container).remove();
        }
        modelName = 'Question';
        targetUrl = modal_delete_test_question_url;
        postData = { test_question_uuid: uuid };
        deleteRecord(targetUrl, postData, removeSlot, 'removeSlot', modelName);

    });

    //edit test question
    $(".test_questions_main-d").on('click', '.edit_test_question-d', function(e) {
        let form = '';
        let elm = $(this);
        form = $('#frm_test_question-d');

        let container = $(elm).parents('.single_test_question-d');
        let uuid = $(container).find('.question_uuid-d').val();

        // console.log(uuid);

        let body = $(container).find('.body-d').text();
        let answer = $(container).find('.correct_answer-d').text();

        if ($(elm).parents('test_questions_main-d').length > 0) {
            $(form).parents().find('.test_questions_main-d').html('');
        }

        $(form).find("#test_question_uuid-d").val(uuid).attr('value', uuid);
        $(form).find("#test_title-d").text(body).attr('value', body);
        $(form).find("#test_question_answer-d").text(answer).attr('value', answer);

        body = $(form).find("#test_title-d").text(body).attr('value', body);



    });

    /**
     * Reset Test Question Form
     *
     * @param {DomElement} form
     */
    function resetTestQuestionForm(form) {
        $(form).find('#test_title-d').val('').attr('value', '');
        $(form).find('#test_question_answer-d').val('').attr('value', '');
        $(form).find('#test_question_uuid-d').val('').attr('value', '');
    }


    /**
     * validate Boolean Question
     */

    $('#frm_boolean_question-d').validate({
        ignore: ".ignore",
        rules: {
            question_body: {
                required: true,
                minlength: 5
            },
            // boolean_option_1: {
            //     required: true,
            //     minlength: 1
            // },
            // boolean_option_2: {
            //     required: true,
            //     minlength: 1,
            // },
        },
        messages: {
            question_body: {
                required: "Question is required",
                minlength: "Question should have atleast 5 character",
            },
            // boolean_option_1: {
            //     required: "Option 1 is required",
            //     minlength: "Option should have atleast 1 character",
            // },
            // boolean_option_2: {
            //     required: "Option 2  is required",
            //     minlength: "Option should have atleast 1 character",
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
            var selectedOption = $(".cb_is_correct_option-d:checked");
            if (selectedOption.length < 1) {
                errorAlert('Please select a choice');
                return false;
            }

            let answers = [];
            $('.frm_choices_container-d').find('.frm_single_choice_container-d').each(function(i, container) {
                let cbx = $(container).find('.cb_is_correct_option-d');

                // determine correct ans
                let is_correct = false;
                if ($(cbx).is(':checked')) {
                    is_correct = true;
                }

                // determine ans_uuid
                let ans_uuid = null;
                if ($(cbx).attr('value') && ('' != $(cbx).attr('value'))) { // case of update
                    ans_uuid = $(cbx).attr('value');
                }

                // determine ans_body
                let ans_body = $(container).find('.txt_option_body-d').val();
                answers.push({
                    is_correct: is_correct,
                    body: ans_body,
                    answer_uuid: ans_uuid
                });
            });
            $(form).find('#answers_json-d').val(JSON.stringify(answers));

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
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
                            // console.log(response.data);
                            let model = response.data;

                            let clonedElm = ''
                            if ($('.q_uuid_' + model.uuid).length > 0) {
                                clonedElm = $('.q_uuid_' + model.uuid);
                            } else {
                                clonedElm = $('#cloneable_single_question_container-d').clone();
                                $(clonedElm).removeAttr('id').addClass('q_uuid_' + model.uuid);
                            }

                            // determine if existing or new one
                            $(clonedElm).find('.question_uuid-d').val(model.uuid);
                            $(clonedElm).find('.question_body-d').text(model.body);

                            // model choices
                            $(clonedElm).find('.question_choices_container-d').html('');
                            $.each(model.choices, function(i, choice) {
                                let clonedChoice;
                                if ($('.ans_uuid_' + choice.uuid).length > 0) {
                                    clonedChoice = $('.ans_uuid_' + choice.uuid);
                                } else {
                                    clonedChoice = $('#cloneable_single_choice_container-d').clone();
                                    $(clonedChoice).removeAttr('id').addClass('ans_uuid_' + choice.uuid);
                                }

                                $(clonedChoice).find('.rb_choice-d')
                                    .addClass('ans_uuid_' + choice.uuid)
                                    .attr('name', 'q_' + model.uuid + '_ans')
                                    .attr('disabled', 'disabled')
                                    .val(choice.uuid).attr('value', choice.uuid);
                                if (model.correct_answer_id == choice.id) {
                                    $(clonedChoice).find('.rb_choice-d').attr('checked', 'checked');
                                }

                                $(clonedChoice).find('.choice_body-d').text(choice.body);
                                if ($('.ans_uuid_' + choice.uuid).length < 1) {
                                    $(clonedElm).find('.question_choices_container-d').append(clonedChoice);
                                }
                            });

                            // question choices container
                            if ($('.uuid_' + model.uuid).length < 1) {
                                $('.quiz_questions_main_container-d').append(clonedElm);
                            }
                            $('.quiz_questions_main_container-d').find('.question_serial-d').each(function(i, itemElm) {
                                console.log($(itemElm).length, i);
                                $(itemElm).text(getPaddedString(i + 1));
                            });
                            resetBooleanQuizForm(form);
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
                    response = xhr.responseJSON;

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


    /**
     * Reset Boolean Quiz Form
     *
     * @param {DomElement} form
     */
    function resetBooleanQuizForm(form) {
        $(form).find('#question_uuid-d').val('').attr('value', '');
        $(form).find('#answers_json-d').val('').attr('value', '');
        $(form).find('.txtarea_q_body-d').val('').attr('value', '').text('');
        $(form).find('.txt_option_body-d').val('').attr('value', '');
        $(form).find('.cb_is_correct_option-d').prop('checked', false);
    }

    //delete boolean question
    $(".quiz_questions_main_container-d").on('click', '.delete_boolean_question-d', function(e) {
        let form = '';

        let elm = $(this);
        form = $('#frm_boolean_question-d');

        let container = $(elm).parents('.single_question_container-d');
        let uuid = $(container).find('.question_uuid-d').val();

        var removeTrueFalse = function() {
            $(form).find('#question_uuid-d').val('').attr('value', '');
            $(container).remove();
        }
        modelName = 'Quiz Question';
        targetUrl = modal_quiz_question_url;
        postData = { question_uuid: uuid };
        deleteRecord(targetUrl, postData, removeTrueFalse, 'removeTrueFalse', modelName);

    });

    //edit boolean quiz
    $(".quiz_questions_main_container-d").on('click', '.edit_boolean_question-d', function(e) {
        let form = '';
        let elm = $(this);
        form = $('#frm_boolean_question-d');

        let container = $(elm).parents('.single_question_container-d');
        let q_uuid = $(container).find('.question_uuid-d').val();
        let q_body = $(container).find('.question_body-d').text().trim();

        $(form).find('.frm_choices_container-d').html('');
        $(container).find('.single_choice_container-d').each(function(i, itemElm) {
            let clonedElm = $('#cloneable_frm_single_choice_container-d').clone();
            $(clonedElm).removeAttr('id');
            let rb = $(itemElm).find('.rb_choice-d');
            let choice_body = $(itemElm).find('.choice_body-d').text();
            $(clonedElm).find('.txt_option_body-d').val(choice_body).attr('value', choice_body);
            let ans_uuid = $(rb).val();
            console.log(ans_uuid);
            $(clonedElm).find('.cb_is_correct_option-d').val(ans_uuid).attr(ans_uuid);
            if ($(rb).is(':checked')) {
                $(clonedElm).find('.cb_is_correct_option-d').attr('checked', 'checked')
            }
            $(form).find('.choice_body')
            $(form).find('.frm_choices_container-d').append(clonedElm);
        });
        $(form).find('.txtarea_q_body-d').val(q_body).attr('value', q_body).html(q_uuid);
        $(form).find('#question_uuid-d').val(q_uuid).attr('value', q_uuid);
        $(form).find('.cb_is_correct_option-d').removeAttr('disabled');
    });

    $('form').on('click', '.chkbx_choice-d', function(e) {
        $('.chkbx_choice-d').prop("checked", false).removeAttr('checked');
        $(this).attr('checked', 'checked').prop("checked", true);
    });

    //  #add_quiz_type-d
    $('#add_quiz_type-d').on('change', '#ddl_course_uuid-d', function(e) {
        let elm = $(this);
        let selection = $(elm).val();
        let data = { 'course_uuid': selection };
        let form = $(this).parents('form');
        $.ajax({
            url: quiz_get_slots_by_course,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                console.log(response);
                if (response.status) {
                    let model = response.data;
                    let slots = model.slots;
                    let ddlSlots = $(form).find('#ddl_course_slot-d');
                    $(form).find('#ddl_course_slot-d').html('');
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




});
