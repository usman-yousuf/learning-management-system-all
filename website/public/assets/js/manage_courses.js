$(function(event) {
    // Activity Modal - START
    // mark selection
    $('#activity_type_modal-d').on('click', '.activity_card-d', function(e) {
        let elm = $(this);
        // set actuve class to selected nature container
        $('.activity_card-d').removeClass('active');
        $(elm).addClass('active');

        // set hidden field value
        let course_nature = $(elm).attr('data-course_nature');
        $('#hdn_course_nature_selection-d').val(course_nature).attr('value', course_nature);
    });
    // Activity Modal - END

    //  course details modal - START

    //  Course basics - START

    // click next btn on activity selection modal for course
    $('.btn_activity_modal_next-d').on('click', function(e) {
        let selectedCourseNature = $('#hdn_course_nature_selection-d').val();
        if ('' == selectedCourseNature.trim()) {
            Swal.fire({
                title: 'Error',
                text: 'Please Choose Course Nature',
                icon: 'error',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                // do nothing
            });
            return false;
        }
        if (selectedCourseNature == 'video') {
            switchModal('activity_type_modal-d', 'video_course_details_modal-d');
        } else {
            switchModal('activity_type_modal-d', 'course_details_modal-d');
        }
    });

    // trigger upload file btn click
    $('#nav_course_detail').on('click', '.click_course_image-d', function(e) {
        $(this).parents('.file-loading').find('#upload_course_image-d').trigger('click');
    });

    // course details submit - START
    $('#frm_course_details-d').validate({
        ignore: ".ignore",
        rules: {
            course_image: {
                required: true
            },
            title: {
                required: true,
                minlength: 3,
            },
            start_date: {
                required: true,
            },
            end_date: {
                required: true,
            },
            course_category_uuid: {
                required: true,
            },
            description: {
                required: true,
            }
        },
        messages: {
            course_image: {
                required: "Cover Image is Required"
            },
            title: {
                required: "Title is Required",
                minlength: "Title Should have atleast 3 characters",
            },
            start_date: {
                required: "Start Date is Required",
            },
            end_date: {
                required: "End Date is Required",
            },
            course_category_uuid: {
                required: "Category is Required",
            },
            description: {
                required: "Description is Required.",
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
                            // window.location.href = APP_URL;
                            let course_uuid = response.data.uuid;
                            let teacher_uuid = response.data.teacher.uuid;
                            $('.hdn_course_uuid-d').val(course_uuid).attr('value', course_uuid);
                            $('#hdn_teacher_uuid-d').val(teacher_uuid).attr('value', teacher_uuid);
                            $('.nav_item_trigger_link-d').removeClass('disabled');
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

    //  Course basics - START




    // course fee - START

    // hide elms by default
    $('#handout_section-d').hide();
    $('#course_detail-d').hide();

    // show|hide fee section  based on if course if free
    $('#frm_course_fee-d').on('click', '.rb_course_free-d', function(e) {
        let is_course_free = $(this).attr('value');
        if ('1' == is_course_free) {
            $('#handout_section-d').hide();
            $('#course_detail-d').hide();
        } else {
            $('#handout_section-d').show();
            $('#course_detail-d').show();
        }
    });

    // validate an submit fee modal
    $('#frm_course_fee-d').validate({
        ignore: ".ignore",
        rules: {
            is_course_free: {
                required: true
            },
            is_handout_free: {
                required: {
                    depends: function(element) {
                        return $("#rb_is_course_paid:checked")
                    }
                }
            },
            price_usd: {
                required: {
                    depends: function(element) {
                        return $("#rb_is_course_paid:checked")
                    }
                },
                min: 1,
            },
            discount_usd: {
                // required: true,
                max: 100,
                min: 0,
            },
            price_pkr: {
                required: {
                    depends: function(element) {
                        return $("#rb_is_course_paid:checked")
                    }
                },
                min: 1,
            },
            discount_pkr: {
                // required: true,
                max: 100,
                min: 0,
            },
        },
        messages: {
            is_course_free: {
                required: "Course Fee Check is Required"
            },
            is_handout_free: {
                required: "Handout Fee Check is Required"
            },
            title: {
                required: "Title is Required",
                minlength: "Title Should have atleast 3 characters",
            },
            start_date: {
                required: "Start Date is Required",
            },
            end_date: {
                required: "End Date is Required",
            },
            course_category_uuid: {
                required: "Category is Required",
            },
            description: {
                required: "Description is Required.",
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
            var current_form = $(form).serialize();
            var base_form = $('#frm_course_details-d').serialize();
            var form_data = base_form + "&" + current_form;
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: form_data,
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
                            let course_uuid = response.data.uuid;
                            let teacher_uuid = response.data.teacher.uuid;
                            $('.hdn_course_uuid-d').val(course_uuid).attr('value', course_uuid);
                            $('#hdn_teacher_uuid-d').val(teacher_uuid).attr('value', teacher_uuid);
                            $('.nav_item_trigger_link-d').removeClass('disabled');
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
    // course fee - END




    // course outline - START

    // edit an outline
    $('.outlines_container-d').on('click', '.edit_outline-d', function(e) {
        let form = $('#course_outline_form-d');
        let elm = $(this);
        let container = $(elm).parents('.single_outline_container-d');
        let title = $(container).find('.outline_title-d').text();
        let uuid = $(container).find('.course_outline_uuid-d').val();
        let duration = $(container).find('.outline_duration-d').text();
        duration = duration.replace(' Hrs', '').split(':');

        $(form).find('#outline_title-d').val(title).attr('value', title);
        $(form).find('#duration_hrs-d').val(duration[0]).attr('value', duration[0]);
        $(form).find('#duration_mins-d').val(duration[1]).attr('value', duration[1]);
        $(form).find('#hdn_course_outline-d').val(uuid).attr('value', uuid);
    });

    $('.outlines_container-d').on('click', '.delete_outline-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.single_outline_container-d');
        let uuid = $(container).find('.course_outline_uuid-d').val();
        var removeOutline = function() {
            $(container).remove();
        }
        modelName = 'Outline';
        targetUrl = modal_delete_outline_url;
        postData = { course_outline_uuid: uuid };
        deleteRecord(targetUrl, postData, removeOutline, 'removeOutline', modelName);
    });

    // validate and submit form
    $('#course_outline_form-d').validate({
        ignore: ".ignore",
        rules: {
            duration_hrs: {
                required: true,
                min: 0,
            },
            duration_mins: {
                required: true,
                min: 0,
                max: 59,
            },
            course_title: {
                required: true,
                minlength: 5,
            }
        },
        messages: {
            duration_hrs: {
                required: "Hours is Required",
                min: "Hour must be a Non-Negetive number",
            },
            duration_mins: {
                required: "Minutes is Required",
                min: "Minute Must be a Nono-Negetive Number",
                max: 'Minute value cannot exceed 59'
            },
            course_title: {
                required: "Title is Required.",
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
            // console.log('submit handler');
            var current_form = $(form).serialize();
            var base_form = $('#frm_course_details-d').serialize();
            var form_data = base_form + "&" + current_form;

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: form_data,
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
                            if ($('.cloneable_outline-d').length > 0) {
                                let clonedElm = $('#cloneable_outline-d').clone();
                                $(clonedElm).removeAttr('id');
                                $(clonedElm).find('.outline_title-d').text(model.title);
                                $(clonedElm).find('.outline_duration-d').text(model.duration_hrs + ':' + model.duration_mins + ' Hrs');
                                $(clonedElm).find('.course_outline_uuid-d').val(model.uuid).attr('value', model.uuid);
                                $(".outlines_container-d").append(clonedElm);
                                $(".outlines_container-d").find('.outline_serial-d').each(function(i, elm) {
                                    $(elm).text(i + 1);
                                });
                                $('.no_item_container-d').remove(); // remove no records container
                                $(form).trigger('reset'); // reset form
                            }
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
            return false;
        }
    });
    // course outline - END





    // video course conetnt - START
    $('#video_course_content_form-d').on('click', '#trigger_video_course_upload-d', function(e) {
        $(this).parents('.file-loading').find('#upload_course_content-d').trigger('click');
    });

    // working on edit of course content
    $('.video_course_content_container-d').on('click', '.edit_video_content-d', function(e) {
        let form = $('#video_course_content_form-d');
        let elm = $(this);
        let container = $(elm).parents('.video_course_single_container-d');
        let thumbnailSrcLink = $(container).find('.video_course_content_thumbnail-d').attr('src');
        let image_path = thumbnailSrcLink.replace(ASSET_URL + '/', '');
        let link = $(container).find('.video_course_link-d').attr('href');
        let title = $(container).find('.video_course_title-d').text().trim();
        let duration = $(container).find('.video_course_duration-d').text();
        duration = duration.replace(' Hrs', '').split(':');
        let uuid = $(container).find('.course_video_uuid-d').val();

        $(form).find('#video_course_url_link-d').val(link).attr('value', link);
        $(form).find('#video_course_title-d').val(title).attr('value', title);
        $(form).find('#content_image-d').attr('src', thumbnailSrcLink);
        $(form).find('#hdn_content_image-d').val(image_path).attr('value', image_path);
        $(form).find('#content_duration_hrs-d').val(duration[0]).attr('value', duration[0]);
        $(form).find('#content_duration_mins-d').val(duration[1]).attr('value', duration[1]);

        $(form).find('#hdn_video_course_content_uuid-d').val(uuid).attr('value', uuid);
    });

    // working on edit of course content
    $('.video_course_content_container-d').on('click', '.delete_video_content-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.video_course_single_container-d');
        let uuid = $(container).find('.course_video_uuid-d').val();
        var removeCourseContent = function() {
            $(container).remove();
        }
        modelName = 'Course Video';
        targetUrl = modal_delete_video_content_url;
        postData = { course_content_uuid: uuid };
        deleteRecord(targetUrl, postData, removeCourseContent, 'removeCourseContent', modelName);
    });

    $('#video_course_content_form-d').validate({
        ignore: ".ignore",
        rules: {
            content_title: {
                required: true,
                minlength: 5,
            },
            duration_hrs: {
                // required: true,
                min: 0,
            },
            duration_mins: {
                required: true,
                min: 0,
                max: 59,
            },
            url_link: {
                required: true,
                url: true,
            },
            content_image: {
                required: true,
            },
        },
        messages: {
            content_title: {
                required: "Title is Required",
                minlength: "Title Should have atleast 05 characters",
            },
            duration_hrs: {
                // required: "Hour is Required.",
                min: "Hour value cannot be less than 0",
            },
            duration_mins: {
                required: "Minute value is Required.",
                min: "Minute value cannot be less than 0",
                max: "Minute value cannot be more than 59",
            },
            url_link: {
                required: "URL is Required.",
                url: 'URL link must be a valid URL',
            },
            content_image: {
                required: "Content Thumbnail is required"
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
            // console.log('submit handler');
            var current_form = $(form).serialize();
            var base_form = $('#frm_course_details-d').serialize();
            var form_data = base_form + "&" + current_form;

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: form_data,
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
                            if ($('#cloneable_video_course_content-d').length > 0) {
                                let clonedElm = $('#cloneable_video_course_content-d').clone();
                                $(clonedElm).removeAttr('id');
                                $(clonedElm).find('.video_course_content_thumbnail-d').attr('src', ASSET_URL + '/' + model.content_image);
                                $(clonedElm).find('.video_course_title-d').text(model.title.trim());
                                $(clonedElm).find('.video_course_link-d').attr('href', model.url_link);
                                $(clonedElm).find('.video_course_duration-d').text(model.duration_hrs + ':' + model.duration_mins + ' Hrs');
                                $(clonedElm).find('.course_video_uuid-d').val(model.uuid).attr('value', model.uuid);
                                $(".video_course_content_container-d").append(clonedElm);
                                // $('.no_item_container-d').remove(); // remove no records container
                                $(form).trigger('reset'); // reset form
                                $('#content_image-d').attr('src', $('#content_image-d').attr('data-default_path'));
                            }
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
            return false;

            return false;
        }
    });

    // video course conetnt - END





    //



    // course details modal - END

});
