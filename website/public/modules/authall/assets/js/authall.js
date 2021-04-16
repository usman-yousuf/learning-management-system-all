$(function(event) {
    // Validate and then process login form
    $('#frm_login-d').validate({
        ignore: ".ignore",
        rules: {
            email: {
                required: true,
                email: true,
                minlength: 5,
            },
            password: {
                required: true,
                minlength: 8
            },
        },
        messages: {
            email: {
                required: "Email is Required",
                minlength: "Email Should have atleast 5 characters",
                email: "Email Format is not valid"
            },
            password: {
                required: "Password is Required.",
                minlength: "Password Should have atleast 8 characters",
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
            // console.log('submit handler');
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
                        window.location.href = APP_URL;
                    });
                },
                error: function(xhr, message, code) {
                    response = xhr.responseJSON;
                    if (404 == response.exceptionCode) {
                        let container = $('.pswd_password-d').parent();
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


    // Validate and then process registeration form
    $('#frm_register-d').validate({
        ignore: ".ignore",
        rules: {
            email: {
                required: true,
                minlength: 6,
            },
            first_name: {
                required: true,
                minlength: 3
            },
            username: {
                required: true,
                minlength: 3
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: '.pswd_password-d'
            },
        },
        messages: {
            email: {
                required: "Email is Required",
                minlength: "Email Should have atleast 5 characters",
                email: "Email Format is not valid"
            },
            first_name: {
                required: "First Name is Required.",
                minlength: "First Name Should have atleast 3 characters",
            },
            username: {
                required: "Username is Required.",
                minlength: "Username Should have atleast 3 characters",
            },
            password: {
                required: "Password is Required.",
                minlength: "Password Should have atleast 8 characters",
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
            // console.log('submit handler');
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
                        window.location.href = verify_account_page_link + '?email=' + response.user.email;
                    });
                },
                error: function(xhr, message, code) {
                    response = xhr.responseJSON;
                    if (404 == response.exceptionCode) {
                        let container = $('.pswd_password-d').parent();
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

    //
    $('.v_code-d').on('keyup', function(e) {
        let elm = $(this);
        if (e.keyCode >= 48 && e.keyCode <= 57) {
            if ($(elm).hasClass('last-d') == false) {
                $(elm).closest('.code_border-s').next().find('.v_code-d').focus();
            }
        }
    });
});