document.addEventListener('DOMContentLoaded', function() {

    // var clickelement = document.getElementsByClassName("search_dropdown-d");
    // Array.from(clickelement).forEach( elm => {
    //     elm.addEventListener("click" , showDropMenu);
    // });

    // function showDropMenu() {
    //     this.classList.add("outline_none-s");
    //     document.getElementById("search_ref_option-d").classList.remove("display_none-s");
    //     document.getElementById("search_ref_option-d").classList.toggle("display_block-s");
    // }
    // // close dropdown when click anywhere else
    // onclick = function(event) {
    //     if (!event.target.matches('.search_dropdown-d')) {
    //         var dropdowns = document.getElementsByClassName("search_ref_option-d");
    //         var i;
    //         for (i = 0; i < dropdowns.length; i++) {
    //             var openDropdown = dropdowns[i];
    //             if (openDropdown.classList.contains('display_block-s')) {
    //                openDropdown.classList.remove('display_block-s');
    //             }
    //         }
    //     }
    // }

});

$(function(event) {
    // add question regarding a course - Course General Question
    $('#add_course_question-d').validate({
        ignore: ".ignore",
        rules: {
            body: {
                required: true,
                minlength: 5,
            },
        },
        messages: {
            body: {
                required: "Question body  is Required",
                minlength: "Question body should have atleast 5 characters",
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
                        // window.location.href = APP_URL;
                        // window.location.href = Student_Course_Detail_Page;
                        $(form).parents('.modal').modal('hide');
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
                            // location.reload();
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

    // upload assignment , student calender activity 
    // $('#student_submit_assignment-d').validate({
    //     ignore: ".ignore",
    //     rules: {
    //         upload_assignment_image: {
    //             required: true,
    //         },
    //     },
    //     messages: {
    //         upload_assignment_image: {
    //             required: "Please upload your assignment",
    //         }
    //     },
    //     errorPlacement: function(error, element) {
    //         $('#' + error.attr('id')).remove();
    //         error.insertAfter(element);
    //         $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
    //     },
    //     success: function(label, element) {
    //         $(element).removeClass('error');
    //         $(element).parent().find('span.error').remove();
    //     },
    //     submitHandler: function(form) {
    //         $.ajax({
    //             url: $(form).attr('action'),
    //             type: 'POST',
    //             dataType: 'json',
    //             data: $(form).serialize(),
    //             beforeSend: function() {
    //                 showPreLoader();
    //             },
    //             success: function(response) {
    //                 Swal.fire({
    //                     title: 'Success',
    //                     text: response.message,
    //                     icon: 'success',
    //                     showConfirmButton: false,
    //                     timer: 2000
    //                 }).then((result) => {
    //                     alert('ok');
    //                     // // window.location.href = APP_URL;
    //                     // // window.location.href = Student_Course_Detail_Page;
    //                     // $(form).parents('.modal').modal('hide');
    //                 });
    //             },
    //             error: function(xhr, message, code) {
    //                 response = xhr.responseJSON;
    //                 if (404 == response.exceptionCode) {
    //                     let container = $('#txt_forgot_pass_email-d').parent();
    //                     if ($(container).find('.error').length > 0) {
    //                         $(container).find('.error').remove();
    //                     }
    //                     $(container).append("<span class='error'>" + response.message + "</span>");
    //                 } else {
    //                     Swal.fire({
    //                         title: 'Error',
    //                         text: response.message,
    //                         icon: 'error',
    //                         showConfirmButton: false,
    //                         timer: 2000
    //                     }).then((result) => {
    //                         // location.reload();
    //                         // $('#frm_donate-d').trigger('reset');
    //                     });
    //                 }
    //                 // console.log(xhr, message, code);
    //                 hidePreLoader();
    //             },
    //             complete: function() {
    //                 hidePreLoader();
    //             },
    //         });
    //         return false;
    //         // add Question
    //     }
    // });
});


//     // quiz start page
//        // add Question
//    $('#add_Question-d').validate({
//         ignore: ".ignore",
//         rules: {

//         },
//         messages: {

//         },
//         errorPlacement: function(error, element) {
//             $('#' + error.attr('id')).remove();
//             error.insertAfter(element);
//             $('#' + error.attr('id')).replaceWith('<span id="' + error.attr('id') + '" class="' + error.attr('class') + '" for="' + error.attr('for') + '">' + error.text() + '</span>');
//         },
//         success: function(label, element) {
//             $(element).removeClass('error');
//             $(element).parent().find('span.error').remove();
//         },
//         submitHandler: function(form) {
//             $.ajax({
//                 url: $(form).attr('action'),
//                 type: 'GET',
//                 dataType: 'json',
//                 data: $(form).serialize(),
//                 beforeSend: function() {
//                     showPreLoader();
//                 },
//                 success: function(response) {
//                     Swal.fire({
//                         title: 'Success',
//                         text: response.message,
//                         icon: 'success',
//                         showConfirmButton: false,
//                         timer: 2000
//                     }).then((result) => {
//                         // window.location.href = APP_URL;
//                         // window.location.href = Student_Course_Detail_Page;
//                     });
//                 },
//                 error: function(xhr, message, code) {
//                     response = xhr.responseJSON;
//                     if (404 == response.exceptionCode) {
//                         let container = $('#txt_forgot_pass_email-d').parent();
//                         if ($(container).find('.error').length > 0) {
//                             $(container).find('.error').remove();
//                         }
//                         $(container).append("<span class='error'>" + response.message + "</span>");
//                     } else {
//                         Swal.fire({
//                             title: 'Error',
//                             text: response.message,
//                             icon: 'error',
//                             showConfirmButton: false,
//                             timer: 2000
//                         }).then((result) => {
//                             // location.reload();
//                             // $('#frm_donate-d').trigger('reset');
//                         });
//                     }
//                     // console.log(xhr, message, code);
//                     hidePreLoader();
//                 },
//                 complete: function() {
//                     hidePreLoader();
//                 },
//             });
//             return false;
//         }
//     });
