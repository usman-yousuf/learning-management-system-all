
$(function(event) {
    // click stats container and show relavent content
    $('#student_course_details_stats_container-d').on('click', '.course_stats-d', function(e) {
        // alert('helo');
        $('.course_stats-d').removeClass('active');
        let elm = $(this);
        $(elm).addClass('active');

        let targetElm = '#' + $(elm).attr('data-target_elm');
        // console.log(targetElm);
        $('.main_work_container-d').hide();
  
        $(targetElm).show().removeClass('d-none');
        // $('.main_work_container-d').find(targetElm).show();

    });

        const container = document.querySelector('.rating');
        const items = container.querySelectorAll('.rating-item')
        container.onclick = e => {
        const elClass = e.target.classList;
        // change the rating if the user clicks on a different star
        if (!elClass.contains('active')) {
            items.forEach( // reset the active class on the star
            item => item.classList.remove('active')
            );
            console.log(e.target.getAttribute("data-rate"));
            elClass.add('active'); // add active class to the clicked star
            }
        };


        //    // add Question 
        // $('#add_Question-d').validate({
        //     ignore: ".ignore",
        //     rules: {
        //         body: {
        //             required: true,
        //             minlength: 5,
        //         },
        //     },
        //     messages: {
        //         body: {
        //             required: "Question body  is Required",
        //             minlength: "Question body should have atleast 5 characters",
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
        //                     // window.location.href = APP_URL;
        //                     window.location.href = Student_Course_Detail_Page;
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
        //     }
        // });
});