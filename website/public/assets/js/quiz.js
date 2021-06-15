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
            add_boolean_question_textarea: {
                required: true,
                minlength: 5
            },
            boolean_option_1: {
                required: true,
                minlength: 1
            },
            boolean_option_2: {
                required: true,
                minlength: 1,
            },

        },
        messages: {
            add_boolean_question_textarea: {
                required: "Question is required",
                minlength: "Question should have atleast 5 character",
            },
            boolean_option_1: {
                required: "Option 1 is required",
                minlength: "Option should have atleast 1 character",
            },
            boolean_option_2: {
                required: "Option 2  is required",
                minlength: "Option should have atleast 1 character",
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
            let answers = [];
            $('.singe_ans_container-d').each(function(index, container) {
                let is_correct = false;
                let checkbox = $(container).find("input[type=checkbox]");
                if ($(checkbox).is(":checked")) {
                    console.log('fhdfdi');
                    is_correct = true;
                }
                let ans_uuid = null;
                if($(checkbox).attr('value') && ('' != $(checkbox).attr('value')) ){
                    ans_uuid = $(checkbox).attr('value');
                }
                let body = $(container).find('.choice_option-d').val();
                answers.push({ is_correct: is_correct, body: body, answer_uuid: ans_uuid });
            });
            $(form).find('.all_answers-d').val(JSON.stringify(answers));

            // console.log(answers);
            // return false;
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
                            let choices = model.choices;
                            // console.log(model);
                            // return false;

                            let clonedQuestion;
                            // debugger;
                            if ($('.uuid_' + model.uuid).length > 0) {
                                clonedQuestion = $('.uuid_' + model.uuid);
                                
                            } else {
                                if ($('#single_clonable_boolean_question-d').length > 0) {
                                    clonedQuestion = $('#single_clonable_boolean_question-d').clone();
                                    $(clonedQuestion).removeAttr('id');
                                }
                            }

                            // let clonedQuestion = $('#single_clonable_boolean_question-d');
                            
                            // clonedQuestion = clonedQuestion.clone();
                            // $(clonedQuestion).removeAttr('id');
                        
                            $(clonedQuestion).find('.quiz_question_uuid-d').val(model.uuid);
                            $(clonedQuestion).find('.boolean_question_body-d').text(model.body);

                            $.each(choices, function(i, choice){
                                // existingElm = $('.rb_choice-d'). "[name='" + current + "']"); 

                                let existingElm = $("[value='"+choice.uuid+"']");
                                // console.log(existingElm);
                                let clonedOption = '';
                                if(existingElm.length > 0){
                                    clonedOption = existingElm;
                                }
                                else{
                                    clonedOption = $('#single_cloneable_option-d').clone();
                                    $(clonedOption).removeAttr('id');
                                    $(clonedOption).find('.correct_answer_id-d').attr('name', 'q_'+model.uuid+'_ans');
                                }


                                $(clonedOption).find('.correct_answer-d').text(choice.body);
                                $(clonedOption).find('.correct_answer_id-d').val(choice.uuid);
                                
                                if(model.correct_answer_id == choice.id){
                                    $(clonedOption).find('.correct_answer_id-d').attr('checked', 'checked');
                                }

                                if(existingElm.length < 1){
                                    $(clonedQuestion).find('.multiple_boolean_cloned-d').append(clonedOption);
                                }
                            });

                            if($('.uuid_' + model.uuid).length > 0)
                            {
                                // $('.boolean_container_main-d').text(clonedQuestion);
                                $('.boolean_container_main-d').append(clonedQuestion);
                            }
                            // $('.boolean_container_main-d').append(clonedOption);
                            // $(clonedQuestion).find('.multiple_boolean_cloned-d').append(clonedOption);

                            resetTestQuizForm(form);
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
     * Reset Test Quiz  Form
     *
     * @param {DomElement} form
     */
    function resetTestQuizForm(form) {
        $(form).find('#boolean_question_title-d').val('').attr('value', '');
        $(form).find('#boolean_answer_1-d').val('').attr('value', '');
        $(form).find('#boolean_option_1-d').val('').attr('value', '');
        $(form).find('#boolean_answer_2-d').val('').attr('value', '');
        $(form).find('#boolean_option_2-d').val('').attr('value', '');
    }

    //delete test question
    $(".boolean_container_main-d").on('click', '.delete_boolean_question-d', function(e) {
        let form = '';

        let elm = $(this);
        form = $('#frm_boolean_question-d');

        let container = $(elm).parents('.single_boolean_question-d');
        let uuid = $(container).find('.quiz_question_uuid-d').val();

        console.log(uuid);
        var removeTrueFalse = function() {
            $(container).remove();
        }
        modelName = 'Quiz';
        targetUrl = modal_delete_test_quiz_url;
        postData = { quiz_question_uuid: uuid };
        deleteRecord(targetUrl, postData, removeTrueFalse, 'removeTrueFalse', modelName);

    });

    //edit test quiz
    $(".boolean_container_main-d").on('click', '.edit_boolean_question-d', function(e) {
        let form = '';
        let elm = $(this);
        form = $('#frm_boolean_question-d');

        let container = $(elm).parents('.single_boolean_question-d');
        let uuid = $(container).find('.quiz_question_uuid-d').val();

        // console.log(uuid);

        let body = $(container).find('.boolean_question_body-d').text();
        let answer1= [];
        let answer2;
        let answer_uuid = $(container).find('input');
        let answer = $(container).find('label');
        console.log(answer_uuid);

        $.each(answer, function(index, label){
            // console.log(index, label);
            answer1[index] = label;
            // console.log(answer1)
            // console.log(answer1[key].$(".correct_answer-d"));
        });
        // console.log(answer1[0].outerText);
        if ($(elm).parents('boolean_container_main-d').length > 0) {
            $(form).parents().find('.test_questions_main-d').html('');
        }

        // defaultValue
        $(form).find("#boolean_question_uuid-d").val(uuid).attr('value', uuid);
        $(form).find("#boolean_question_title-d").text(body).attr('value', body);

        $(form).find("#uuid_option_1-d").attr('value', answer_uuid[0].defaultValue);
        $(form).find("#boolean_option_1-d").attr('value', answer1[0].outerText);
        console.log( answer_uuid[0].defaultValue );

        
        var option_1_check = $(answer1[0]).parents('.options-d').find('.rb_choice-d').attr('checked');
        if (typeof option_1_check !== 'undefined' && option_1_check !== false) {
            $(form).find("#uuid_option_1-d").attr('checked', 'checked');
        }
        else{
            $(form).find("#uuid_option_1-d").removeAttr('checked');
        }

        $(form).find("#uuid_option_2-d").attr('value', answer_uuid[1].defaultValue);
        $(form).find("#boolean_option_2-d").attr('value', answer1[1].outerText);
        var option_2_check = $(answer1[1]).parents('.options-d').find('.rb_choice-d').attr('checked');
        if (typeof option_2_check !== 'undefined' && option_2_check !== false) {
            $(form).find("#uuid_option_2-d").attr('checked', 'checked');
        }
        else{
            $(form).find("#uuid_option_2-d").removeAttr('checked');
        }

        body = $(form).find("#test_title-d").text(body).attr('value', body);

    });

    $('form').on('click', '.chkbx_choice-d', function(e){
        $('.chkbx_choice-d').prop("checked", false).removeAttr('checked');
        // $('input:checkbox').attr('checked','checked');

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
