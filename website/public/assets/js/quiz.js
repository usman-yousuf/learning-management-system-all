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
                min: 30,
                max: 180
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
                min: "Minimun quiz duration can not less than 30 minutes",
                max: "Max quiz duration can not exceed 180 minutes",
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
                                type = 'MCQs';
                            }

                            if ($('#cloneable_quiz_container-d').length > 0) {
                                let clonedElm = $('#cloneable_quiz_container-d').clone();
                                $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                $(clonedElm).attr('data-uuid', model.uuid);
                                let linkElm = $(clonedElm).find('.link-d');
                                let link = $(linkElm).attr('href');
                                link = link.replace('______', model.uuid);
                                $(linkElm).attr('href', link);
                                $(clonedElm).find('.title-d').text(model.title).attr('data-course_uuid', model.course.uuid).attr('data-slot_uuid', model.slot.uuid);
                                $(clonedElm).find('.title-d').text(model.title).attr('data-course_uuid', model.course.uuid).attr('data-slot_uuid', model.slot.uuid);
                                $(clonedElm).find('.type-d').text(type);
                                $(clonedElm).find('.duration-d').text(model.duration_mins);
                                $(clonedElm).find('.description-d').text(model.description);
                                $(clonedElm).find('.students_count-d').text(model.description_count);
                                $(clonedElm).find('.due_date-d').text(model.modal_due_date).attr('data-due_date', model.due_date);
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
            test_question: {
                required: true,
                minlength: 5
            },
            // test_answer: {
            //     required: true,
            //     minlength: 5,
            // },
        },
        messages: {
            test_question: {
                required: "Question is required ",
                minlength: "Title Should have atleast 5 characters",
            },
            // test_answer: {
            //     required: "Answer is required",
            //     minlength: "Title Should have atleast 5 characters",
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

                            $(clonedElm).find('.question_uuid-d').val(model.uuid);
                            $(clonedElm).find('.question_body-d').text(model.body);
                            $(clonedElm).find('.correct_answer-d').val(model.correct_answer).text(model.correct_answer);

                            if ($('.uuid_' + model.uuid).length < 1) {
                                $('.test_questions_main-d').append(clonedElm);
                            }

                            $('.quiz_questions_main_container-d').find('.question_serial-d').each(function(i, itemElm) {
                                console.log($(itemElm).length, i);
                                $(itemElm).text(getPaddedString(i + 1));
                            });

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
        let elm = $(this);
        form = $('#frm_test_questions-d');

        let container = $(elm).parents('.single_test_question-d');
        let uuid = $(container).find('.question_uuid-d').val();

        var removeQuestion = function() {
            $(container).remove();
        }
        modelName = 'Question';
        targetUrl = modal_delete_question_url;
        // console.log(modelName, targetUrl)
        // $(container).remove();

        postData = { question_uuid: uuid };
        deleteRecord(targetUrl, postData, removeQuestion, 'removeQuestion', modelName);

    });

    //edit test question
    $(".test_questions_main-d").on('click', '.edit_test_question-d', function(e) {

        let elm = $(this);
        let container = $(elm).parents('.single_test_question-d');

        let uuid = $(container).find('.question_uuid-d').val();
        let question = $(container).find('.question_body-d').text().trim();
        let answer = $(container).find('.correct_answer-d').val();

        let form = $('#frm_test_question-d');

        $(form).find('#test_question-d').val(question).text(question);
        $(form).find('#test_question_answer-d').val(answer).text(answer);
        $(form).find('#question_uuid-d').val(uuid);

        // let uuid = $(container).find('.question_uuid-d').val();

        // // console.log(uuid);

        // let body = $(container).find('.body-d').text();
        // let answer = $(container).find('.correct_answer-d').text();

        // if ($(elm).parents('test_questions_main-d').length > 0) {
        //     $(form).parents().find('.test_questions_main-d').html('');
        // }

        // $(form).find("#test_question_uuid-d").val(uuid).attr('value', uuid);
        // $(form).find("#test_title-d").text(body).attr('value', body);
        // $(form).find("#test_question_answer-d").text(answer).attr('value', answer);

        // body = $(form).find("#test_title-d").text(body).attr('value', body);



    });


    // validate Boolean Question
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

    // ahmed nawaz
    // edit a quiz
    $(".quiz_main_container-d").on('click', '.edit_quiz-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.single_quiz_container-d');

        // get data
        let uuid = $(container).attr('data-uuid');
        let type = $(container).find('.type-d').text().toLowerCase();
        type = (type == 'true false') ? 'boolean' : type;
        let modal = $('#quiz_type_modal');

        let title = $(container).find('.title-d strong').text().trim();
        let duration = $(container).find('.duration-d').text().trim();
        let course_uuid = $(container).find('.title-d').attr('data-course_uuid');
        let slot_uuid = $(container).find('.title-d').attr('data-slot_uuid');
        let due_date = $(container).find('.due_date-d').attr('data-due_date');

        let description = $(container).find('.description-d').text().trim();

        // set type
        selector = '#add_quiz_type-d input[type="radio"][value="' + type + '"]';
        $(selector).trigger('click');

        let form = $(modal).find('#add_quiz_type-d');
        $(form).find('#quiz_title-d').val(title);
        $(form).find('#quiz_duration-d').val(duration);

        (function(next) {
            $(form).find('#ddl_course_uuid-d').val(course_uuid);
            $(form).find('#ddl_course_uuid-d').trigger('change');

            next()
        }(function() {
            $(form).find('#ddl_course_uuid-d').attr('disabled', 'disabled');
            $(form).find('#ddl_course_slot-d').val(slot_uuid);
            $(form).find('#txt_due_date-d').val(due_date);
            $(form).find('.quiz_description-d').val(description);
            $(form).find('#hdn_quiz_uuid-d').val(uuid);

            // $(modal).find('#ddl_course_slot-d').val(model.assignment.slot.uuid).attr('disabled', 'disabled');

            // $(modal).find('#assignment_start_date-d').val(model.assignment.start_date).attr('disabled', 'disabled');
            // $(modal).find('#assignment_due_date-d').val(model.assignment.due_date).attr('disabled', 'disabled');

            // $(modal).find('#total_marks-d').val(model.assignment.total_marks).attr('disabled', 'disabled');
            // $(modal).find('#assignment_title-d').val(model.assignment.title).attr('disabled', 'disabled');
            // $(modal).find('.hdn_assignment_uuid-d').val(model.assignment.uuid).attr('disabled', 'disabled');
            // $(modal).find('.hdn_assignment_media_1-d').val(model.assignment.media_1).attr('disabled', 'disabled');

            // $(modal).find('.btn_assignment_save-d').hide();
            // $(modal).modal('show');
        }))

        $(modal).modal('show');
    });

    // delete a quiz
    $(".quiz_main_container-d").on('click', '.delete_quiz-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.single_quiz_container-d');
        let uuid = $(container).attr('data-uuid');

        var removeQuiz = function() {
            $(container).remove();
        }
        modelName = 'Quiz';
        targetUrl = modal_delete_quiz_url;
        // console.log(modelName, targetUrl)
        // $(container).remove();

        postData = { quiz_uuid: uuid };
        deleteRecord(targetUrl, postData, removeQuiz, 'removeQuiz', modelName);
    })

    // tick an option in question choices
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


    // add another option
    $('#frm_boolean_question-d').on('click', '.btn_add_more_option-d', function(e) {
        let form = $(this).parents('form');
        if ($(form).find('.remove-option-d').length > 3) {
            errorAlert('A Question cannot have more than 4 Options');
            return false;
        }
        let clonedElm = $('#cloneable_frm_single_choice_container-d').clone();
        $(clonedElm).removeAttr('id');
        $(clonedElm).find('.cb_is_correct_option-d').removeAttr('disabled');
        $(form).find('.frm_choices_container-d').append(clonedElm);
    });

    // remove an option from list
    $('#frm_boolean_question-d').on('click', '.remove-option-d', function(e) {
        let elm = $(this);
        let form = $(elm).parents('form');
        if ($(form).find('.remove-option-d').length < 2) {
            errorAlert('Last Option cannot be deleted');
            return false;
        }
        $(elm).parents('.frm_single_choice_container-d').remove();
    });




    /**
     * Reset Test Question Form
     *
     * @param {DomElement} form
     */
    function resetTestQuestionForm(form) {
        $(form).find('#test_question-d').val('').text('');
        $(form).find('#test_question_answer-d').val('').text('');
        $(form).find('#question_uuid-d').val('').attr('value', '');
    }

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

});