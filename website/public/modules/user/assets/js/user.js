$(function(event) {
    // trigger upload wizard for profile image upload
    $('.click_profile_image-d').on('click', function(e) {
        let elm = $(this);
        $('#upload_profile_image-d').trigger('click');
    });

    // trigger upload wizard for profile image upload
    $('.click_certificate_image-d').on('click', function(e) {
        let elm = $(this);
        $(elm).closest('.upload_file_container-d').find('#upload_certificate_image-d').trigger('click');
    });

    $('.click_experience_image-d').on('click', function(e) {
        let elm = $(this);
        $(elm).closest('.upload_file_container-d').find('#upload_experience_image-d').trigger('click');
    });

    if($('#ddl_interests').length > 0)
    {
        $('#ddl_interests').select2({
            placeholder: 'Please Add Interests',
            tags: true,
            tokenSeparators: [',']
        });
    }

    // validate and submit profile form
    $('#frm_profile_setting-d').validate({
        ignore: ".ignore",
        rules: {
            first_name: {
                required: true,
                minlength: 3,
            },
            // last_name: {
            //     required: true,
            //     minlength: 1
            // },
            gender: {
                required: true,
            },
            dob: {
                required: true,
            },
            // address
            address1: {
                required: true,
            },
            // address2: {
            //     required: true,
            // },
            city: {
                required: true,
            },
            country: {
                required: true,
            },
            post_code: {
                required:{
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            phone_number: {
                required: true,
                minlength: 9,
            },
            // mobile_number: {
            //     required: true,
            // },

            // Education
            degree_title: {
                required: true,
            },
            completion_year: {
                required: true,
            },
            university: {
                required: true,
            },
            certification_image: {
                required: true,
            },

            // Experience
            job_experience: {
                required:{
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            teaching_experience: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            experience_image: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            interests: {
                required: true,
            },

            // bank info
            account_title: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            bank_name: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            iban: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            account_number: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            branch_name: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            branch_code: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            swift_code: {
                required: {
                    depends: function(element) {
                        return $("#check_profile_type-d").val('teacher')
                    }
                },
            },
            user_code: {
                required: {
                    depends: function(element) {
                        return $("#user_code-d").val('parent')
                    }
                }
            },
            accept_tos: {
                required: true,
            },
        },
        messages: {
            first_name: {
                required: "First Name is Required.",
                minlength: "First Name Should have atleast 3 characters",
            },
            // last_name: {
            //     required: "Last Name is Required.",
            //     minlength: "Last Name Should have atleast 1 character",
            // },
            gender: {
                required: "Gender is Required.",
            },
            dob: {
                required: "Date of Birth is Required.",
            },

            // address
            address1: {
                required: "Address Line 1 is Required.",
            },
            // address2: {
            //     required: true,
            //     required: "Address Line 2 is Required.",
            // },
            city: {
                required: "City is Required.",
            },
            country: {
                required: "Country is Required.",
            },
            post_code: {
                required: "Post Code is Required.",
            },
            phone_number: {
                required: "Phone Number is Required.",
                minlength: "Min length should have 9 digits"
            },
            // mobile_number: {
            //     required: "Mobile Number is Required.",
            // },

            // Education
            degree_title: {
                required: "Degree Title is Required.",
            },
            completion_year: {
                required: "Completion Year is Required.",
            },
            university: {
                required: "University is Required.",
            },
            certification_image: {
                required: "Certifciation Image is Required.",
            },

            // Expereince
            experience_image: {
                required: "Experience Proof is Required.",
            },
            job_experience: {
                required: "Job Experience is Required.",
            },
            teaching_experience: {
                required: "Teaching Experience is Required.",
            },
            interests: {
                required: "Interest is Required.",
            },

            // bank info
            account_title: {
                required: "Bank Info is Required.",
            },
            bank_name: {
                required: "Bank Name is Required.",
            },
            iban: {
                required: "IBAN is Required.",
            },
            account_number: {
                required: "Account Number is Required.",
            },
            branch_name: {
                required: "Branch Name is Required.",
            },
            branch_code: {
                required: "Branch Code is Required.",
            },
            swift_code: {
                required: "Swift Code is Required.",
            },
            user_code: {
                required: "User code is required.",
            },
            accept_tos: {
                required: "Please Accept the terms and Conditions.",
            }
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
            if ($('#cbx_tos-d').is(':checked') == false) {
                Swal.fire({
                    title: 'Error',
                    text: "Please Accept our terms and conditions!",
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    return false;
                });
                return false;
            }
            // submit ajax
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
                        // window.location.href = login_page_url;
                        console.log(response.data.user.profile_type);
                       
                       
                        if(('' !=response.data.user.profile_type) && (response.data.user.profile_type == 'teacher'))
                        {   
                            if(null !=response.data.approver_id)
                            {
                                window.location.href = TEACHER_DASHBOARD_URL_2;
                            }
                            else  {
                                $('#waiting_popup-d').find('.wait_modal_redirect_url-d').attr('href', ProfileSettingUrl);
                                $('#waiting_popup-d').modal('show');
                            }
                        }
                        else if(response.data.user.profile_type == 'student')
                        {
                            if(null !=response.data.approver_id)
                            {
                                window.location.href = STUDENT_DASHBOARD_URL;
                            }
                            else  {
                                $('#waiting_popup-d').find('.wait_modal_redirect_url-d').attr('href', ProfileSettingUrl);
                                $('#waiting_popup-d').modal('show');
                            }
                            console.log('profile updated successfully');
                            // window.location.reload();

                            // window.location.href = APP_URL; profile setting page of student
                        }
                        else if(response.data.user.profile_type == 'parent ')
                        {
                            // window.location.href = APP_URL; profile setting page of parent
                        }else {
                            return false;
                        }

                        // $('#waiting_popup-d').find('.wait_modal_redirect_url-d').attr('href', ProfileSettingUrl);
                        // $('#waiting_popup-d').modal('show');
                        // window.location.reload();
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
            return false;
        }
    });
});