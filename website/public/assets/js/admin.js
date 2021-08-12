$(document).ready(function() {


    // approve teacher  - START
    $('.frm_approve_teacher-d').each(function() {
        $(this).validate({

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
                                // return false;
                                // return false;

                                $("#approved_teacher_modal-d").modal('show');
                                let uuid = $(form).find('.teacher_uuid-d').val();
                                console.log(uuid);
                                targetId = '#teacher-d' + uuid;
                                $(form).parents(targetId).remove();
                                // $(`#teacher-d${response.data.uuid}`).addClass('d-none');

                                //    location.reload();
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

    // approve course  - START
    $('.frm_approve_teacher_course-d').each(function() {
        $(this).validate({
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
                                $("#approved_teacher_course_modal-d").modal('show');
                                let uuid = $(form).find('.course_uuid-d').val();

                                targetId = '#teacher_course-d' + uuid;
                                console.log(targetId);
                                $(form).parents(targetId).remove();

                                //    location.reload();
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

    // reject teacher profile
    $('.frm_rejection-d').each(function(i, elm) {

        $(elm).validate({
            ignore: ".ignore",
            rules: {
                rejection_description: {
                    required: true,
                    minlength: 5,
                },
            },
            messages: {
                rejection_description: {
                    required: "Rejection Reason is Required",
                    minlength: "Rejection Reason Should have atleast 5 characters",
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
                let profile_uuid = $(form).find('.profile_uuid-d').val();
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
                            // teacher-d5f98092f-c34a-4bab-9761-a4c320df6b80
                            $('.non_approved_teachers_container-d').find('.uuid_' + profile_uuid).remove();
                            // window.location.href = APP_URL;
                            // window.location.href = reset_password_page_url + '?email=' + response.data.email + '&vcode=' + response.data.code;
                            window.location.href = ADMIN_URL;
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
            }
        });
    });

});