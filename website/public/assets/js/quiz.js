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
                        console.log(response);
                        let model = response.data;
                            // existingElm = $('.uuid_' + model.uuid);
                            $('.quiz_type_modal-d').modal('hide');
                            // $(".quiz_type_modal-d").each(function()
                            // {
                            //     $(this).model('hide');
                            // });
                            let new_quiz_el =  $(".new_quiz_course_add-d");
                            $(new_quiz_el).find(".quiz_new_type-d").text(model.type);    
                            $(new_quiz_el).find(".quiz_new_description-d").text(model.description);    
                            $(new_quiz_el).find(".quiz_new_enroll_student-d").text(model.students_count);    
                            $(new_quiz_el).find(".quiz_new_attending_student-d").text(model.students_count);
                                
                            resetOutlineForm(form);
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