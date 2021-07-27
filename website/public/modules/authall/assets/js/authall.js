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
                        console.log(response);
                        // return false;

                        if (response.data.user.profile_type == 'teacher') {
                            if ((response.data.user.profile.approver_id != null) && ('' != response.data.user.profile.approver_id)) {
                                console.log('teacher dashboard')
                                window.location.href = DASHBOARD_URL;
                            }
                            else {
                                alert('ok');
                            }
                        } else if ((response.data.user.profile_type == 'student') && ('' != response.data.user.profile_type)) {
                            window.location.href = STUDENT_DASHBOARD_URL;
                            console.log('student profile setting page')
                        } else if ((response.data.user.profile_type == 'parent') && ('' != response.data.user.profile_type)) {
                            console.log('parent profile setting page');
                        } else {
                            console.log('admin dashboard')
                            window.location.href = ADMIN_DASHBOARD_URL;
                        }
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
                        window.location.href = verify_account_page_link + '?email=' + response.data.user.email;


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

    // forgot password
    $('#frm_fogrot_password-d').validate({
        ignore: ".ignore",
        rules: {
            email: {
                required: true,
                email: true,
                minlength: 5,
            },
        },
        messages: {
            email: {
                required: "Email is Required",
                minlength: "Email Should have atleast 5 characters",
                email: "Email Format is not valid"
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
                        // window.location.href = reset_password_page_url + '?email=' + response.data.email + '&vcode=' + response.data.code;
                        window.location.href = HOMEURL;
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

    // move to next input field
    $('#frm_validate_code-d').on('keydown', '.v_code-d', function(e) {
        let elm = $(this);
        let text = $(elm).val().trim();

        if (text.length > 0) {
            if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                $(elm).val('');
            }
        }
    });

    $('#frm_validate_code-d').on('keyup', '.v_code-d', function(e) {
        let elm = $(this);
        let text = $(elm).val().trim();

        if (text.length > 0) {
            if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                if ($(elm).hasClass('last-d') == false) {
                    $(elm).trigger('focusout');
                    let nextInputElm = $(elm).closest('.code_border-d').next().find('.v_code-d');
                    $(nextInputElm).trigger('focusin').focus();
                }
            }
        }

        // set hidden field in form to send in request
        let codeValue = '';
        $.each($('.v_code-d'), function(index, elm) {
            let elmValue = $(elm).val();
            if (elmValue == '') {
                codeValue = '';
                return;
            }
            codeValue += elmValue;
        });
        $('#hdn_activation_code-d').val(codeValue).attr('value', codeValue);
        $('#hdn_set_pass_activation_code-d').val(codeValue).attr('value', codeValue);
    });

    // validate Activation code
    $('#frm_validate_code-d').validate({
        ignore: ".ignore",
        rules: {
            email: {
                required: true,
                email: true,
                minlength: 5,
            },
            activation_code: {
                required: true,
                minlength: 4
            },
            number_box_1: {
                required: true,
                min: 0,
                max: 9,
                maxlength: 1
            },
            number_box_2: {
                required: true,
                min: 0,
                max: 9,
                maxlength: 1
            },
            number_box_3: {
                required: true,
                min: 0,
                max: 9,
                maxlength: 1
            },
            number_box_4: {
                required: true,
                min: 0,
                max: 9,
                maxlength: 1
            }
        },
        messages: {
            email: {
                required: "Email is Required",
                minlength: "Email Should have atleast 5 characters",
                email: "Email Format is not valid"
            },
            activation_code: {
                required: "Activation Code is Required.",
                minlength: "Activation Code Should have atleast 4 characters",
            },
            number_box_1: {
                required: '***',
                max: 'max 09',
                maxlength: "Length = 1"
            },
            number_box_2: {
                required: '***',
                max: 'max 09',
                maxlength: "Length = 1"
            },
            number_box_3: {
                required: '***',
                max: 'max 09',
                maxlength: "Length = 1"
            },
            number_box_4: {
                required: '***',
                max: 'max 09',
                maxlength: "Length = 1"
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
                    if (response.status) {
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            // $('#validate_code_container-d').hide();
                            // $('#set_password_container-d').show();
                            window.location.href = APP_URL;
                            console.log(response);
                            // return false;
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            // do nothing
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
                        // do nothing
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

    // Resend verification Code to email
    $('#frm_validate_code-d').on('click', '.resend_code-d', function(e) {
        let elm = $(this);
        let targetUrl = $(elm).attr('data-href');
        let targetEmail = $('#hdn_email-d').val();
        let form = $(this).parents('form');

        if (targetEmail == '') {
            Swal.fire({
                title: 'Error',
                text: 'Email incorrect or not provided',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000
            }).then((result) => {
                return false;
            });
            return false;
        }

        $.ajax({
            url: targetUrl,
            type: 'POST',
            dataType: 'json',
            data: { email: targetEmail },
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
                    // do nothing
                    $(form).find('.v_code-d').val('').attr('value', '');
                });
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
                    // don nothing
                    // location.reload();
                });
                // console.log(xhr, message, code);
                hidePreLoader();
            },
            complete: function() {
                hidePreLoader();
            },
        });
    });


    // Set Password
    $('#frm_set_password-d').validate({
        ignore: ".ignore",
        rules: {
            email: {
                required: true,
                email: true,
                minlength: 5,
            },
            activation_code: {
                required: true,
                minlength: 4
            },
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: '.set_pass_pswd-d'
            },
        },
        messages: {
            email: {
                required: "Email is Required",
                minlength: "Email Should have atleast 5 characters",
                email: "Email Format is not valid"
            },
            code: {
                required: "Activation Code is Required.",
                minlength: "Activation Code Should have atleast 4 characters",
            },
            password: {
                required: "Password is Required",
                minlength: "Password Should have atleast 8 characters",
            },
            password_confirmation: {
                required: "Confirm Password is Required",
                minlength: "Password Should have atleast 8 characters",
                equalTo: 'Confirm Password MUST be Equal to Password'
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
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        window.location.href = login_page_url;
                    });
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
                        // don nothing
                        // location.reload();
                    });
                    // console.log(xhr, message, code);
                    hidePreLoader();
                },
                complete: function() {
                    hidePreLoader();
                },
            });
        }
    });


    // popup signup category modal
    $('.open_signup_category-d').on('click', function(e) {
        $('#add_signup_category_modal-d').modal('show');
    });

    // select calendar activity type
    $('#add_signup_category_modal-d').on('click', '.category_signup-d', function(e) {
        let elm = $(this);
        let activity_type = $(elm).attr('data-activity_type');
        $('.category_signup-d').removeClass('active');
        $(elm).addClass('active');
        console.log(activity_type);
        $('#add_signup_category_modal-d').find('.hdn_category_signup-d').val(activity_type).attr('value', activity_type);
    });

    $('#add_signup_category_modal-d').on('click', '.btn_activity_page_next-d', function(e) {
        let categorySingup = $('.hdn_category_signup-d').val();
        // alert(categorySingup);
        if (categorySingup == 'student') {
            window.location.href = STUDENT_SIGNUP;
        } else {
            // alert('parent');
            window.location.href = PARENT_SIGNUP;
        }
    });
});