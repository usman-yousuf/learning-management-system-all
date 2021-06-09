$(document).ready(function(){
    $('#add_quiz_type_modal-d').on('click', function(e) {
        console.log("ok");
        $('#quiz_type_modal').modal('show');
    });


   // add-quiz-type submit - START
   $('#add_quiz_type-d').validate({
    ignore: ".ignore",
    rules: {
        test: {
            required: true
        },
        quiz_title: {
            required: true,
            minlength: 3,
        },
        quiz_duration: {
            required: true,
        },
        comment_text: {
            required: true,
        },
    },
    messages: {
        test: {
            required: "Quiz type is Required"
        },
        quiz_title: {
            required: "Title is Required",
            minlength: "Title Should have atleast 3 characters",
        },
        quiz_duration: {
            required: "Quiz Duration is Required",
        },
        comment_text: {
            required: "Comment is Required",
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
                        // console.log(response);
                        let model = response.data;
                           console.log(model.description);
                            $('.quiz_type_modal-d').modal('hide');
                            let type = 'test';
                            if(model.type == 'boolean')
                            {
                                type = "True False";
                            }
                            else if(model.type == 'mcqs'){
                                type = 'mcqs';
                            }

                            if($('#cloneable_quiz_container-d').length > 0){
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
                                if($('.uuid_'+model.uuid).length > 0){
                                    clonedElm = $('.uuid_'+model.uuid);
                                }
                                else{
                                    if($('#single_clonable_question_test-d').length > 0){
                                        clonedElm = $('#single_clonable_question_test-d').clone();
                                        $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                    }
                                }
                                // console.log(clonedElm);

                                $(clonedElm).find('.question_uuid-d').text(model.uuid);
                                $(clonedElm).find('.body-d').text(model.body);
                                $(clonedElm).find('.correct_answer-d').text(model.correct_answer);
                                
                                if($('.uuid_'+model.uuid).length < 1){
                                    $('.test_questions_main-d').append(clonedElm);
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

        if($(elm).parents('test_questions_main-d').length > 0) {
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
            // let question = document.getElementById("boolean_question_title").value;
            // let value1 = document.getElementById("boolean_option_1-d").value;
            // let value2 = document.getElementById("boolean_option_2-d").value;
            // let value3 = document.getElementById("boolean_answer_1");
            // let value4 = document.getElementById("boolean_answer_2");
            // let check;
            // if(value3.checked || value4.checked)
            // {
            //    check = 1;
            // }

            // let obj = {'body': question, 'is_correct' : check, 'answer_uuid': ''}
            // console.log(obj );
            // console.log(form.serialize(data));
    
            let answers = [];
            $('.singe_ans_container-d').each(function(index, container){
                let is_correct = false;
                if($(container).find("input[type=checkbox]").is(":checked")){
                    console.log('fhdfdi');
                    is_correct = true;
                }
                let body = $(container).find('.txt_ans_body-d').val();
                answers.push({is_correct: is_correct, body:body, answer_uuid:null});
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
                            console.log(response.data);
                            let model = response.data;
                            let choices = model.choices;
                            console.log(choices);
                                    let clonedElm;
                                    if($('.uuid_'+model.uuid).length > 0){
                                        clonedElm = $('.uuid_'+model.uuid);
                                    }
                                    else{
                                        if($('#single_clonable_boolean_question-d').length > 0){
                                            clonedElm = $('#single_clonable_boolean_question-d').clone();
                                            $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                        }
                                    }
                                    // console.log(clonedElm);
    
                                    $(clonedElm).find('.question_uuid-d').text(model.uuid);
                                    $(clonedElm).find('.boolean_question_body-d').text(model.body);
                                    // $(clonedElm).find('options-d');
                                        $.each(choices, function(i, v){
                                            // console.log(i,v);
                                            console.log(v.uuid);
                                            console.log(v.body);
                                            $(clonedElm).find('options-d').find('.correct_answer-d').text(v.body);
                                            $(clonedElm).find('options-d').find('.correct_answer_id-d').text(v.body).attr('value', v.uuid);

                                        });
                                    
                                    if($('.uuid_'+model.uuid).length < 1){
                                        $('.boolean_container_main-d').append(clonedElm);
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
    







});
