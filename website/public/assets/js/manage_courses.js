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
        $('.hdn_course_nature-d').val(course_nature).attr('value', course_nature);
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
            $('#nav-course-slot').hide();
            $('#nav-course-video-content').show();
            // $('#nav-course-handout').hide();
        } else {
            $('#nav-course-slot').show();
            $('#nav-course-video-content').hide();
            // $('#nav-course-handout').show();
        }
        switchModal('activity_type_modal-d', 'course_details_modal-d');

        // if (selectedCourseNature == 'video') {
        //     switchModal('activity_type_modal-d', 'video_course_details_modal-d');
        // } else {
        //     switchModal('activity_type_modal-d', 'course_details_modal-d');
        // }
    });

    // trigger upload file btn click
    $('#nav_course_detail').on('click', '.click_course_image-d', function(e) {
        $(this).parents('.file-loading').find('#upload_course_image-d').trigger('click');
    });


    /**
     * Move to next tab marking the current one as complete
     *
     * @param DomElemenet form
     */
    function moveToNextTab(form) {
        let currentTabContent = $(form).parents('.tab-pane');
        let currentTabId = $(currentTabContent).attr('id');
        let currentTabElm = $(".nav_item_trigger_link-d[href*=" + currentTabId + "]");

        $(currentTabElm).find('.tick-icon-d').show();

        let nextElm = $(currentTabElm).next();
        let nature = $('.hdn_course_nature-d').val();
        if (nature == 'video') {
            if ($(nextElm).attr('id') == 'nav-course-slot') {
                $(nextElm).next().removeClass('disabled').trigger('click');
            } else {
                $(nextElm).removeClass('disabled').trigger('click');
            }
        } else if (nature == 'online') {
            if ($(nextElm).attr('id') == 'nav-course-video-content') {
                $(nextElm).next().removeClass('disabled').trigger('click');
            } else {
                $(nextElm).removeClass('disabled').trigger('click');
            }
        }

    }

    //move to next tab in modal when clicked on next btn
    $('.tab-pane').on('click', '.btn_next_tab-d', function(e) {
        let paneContainer = $(this).parents('.tab-pane');
        // details has no next btn
        if ($(paneContainer).attr('id') == 'nav_course_outline') { // outline container
            let content = $(paneContainer).find('.outlines_container-d').html().trim();
            if ('' != content) {
                moveToNextTab('#course_outline_form-d');
            } else {
                errorAlert('Please Add Some Outlines');
            }
        } else if ($(paneContainer).attr('id') == 'nav_course_slots') { // slots container
            let content = $(paneContainer).find('.slots_container-d').html().trim();
            if ('' != content) {
                moveToNextTab('#course_slots_form-d');
            } else {
                errorAlert('Please Add Some Slots');
            }
        } else if ($(paneContainer).attr('id') == 'nav_course_content') { // Video container
            let content = $(paneContainer).find('.video_course_content_container-d').html().trim();
            if ('' != content) {
                moveToNextTab('#video_course_content_form-d');
            } else {
                errorAlert('Please Add Some Videos');
            }
        } else if ($(paneContainer).attr('id') == 'nav_handout_content') { // Handouts container
            let content = $(paneContainer).find('.course_handout_container-d').html().trim();
            if ('' != content) {
                moveToNextTab('#course_handout_content_form-d');
            } else {
                errorAlert('Please Add Some Handouts');
            }
        }
        // free has no next btn
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
                            $('.course_uuid-d').val(course_uuid).attr('value', course_uuid);
                            $('#hdn_teacher_uuid-d').val(teacher_uuid).attr('value', teacher_uuid);
                            // $('.nav_item_trigger_link-d').removeClass('disabled');

                            // setup min and max dates for slots
                            $('#course_slots_form-d').find('#course_slot_start_date-d').attr('min', response.data.model_start_date).attr('max', response.data.model_end_date);
                            $('#course_slots_form-d').find('#course_slot_end_date-d').attr('min', response.data.model_start_date).attr('max', response.data.model_end_date);

                            console.log(form);
                            moveToNextTab(form);
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
    // $('#handout_section-d').hide();
    // $('#course_detail-d').hide();

    $('#frm_course_setting-d').on('click', '.rb_course_fee-d', function(e) {
        let is_course_free = $(this).attr('value');
        if ('1' == is_course_free) {
            $('#course_amount_container-d').hide();
            $('#handout_price_section-d').hide();
        } else {
            $('#course_amount_container-d').show();
            $('#handout_price_section-d').show();
        }
    });

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

    $('#course_amount_container-d').on('change', '.course_currency-d', function(e) {
        let elm = $(this);
        let selectedCurrency = (elm).val();

        // set price/discount value
        $('.price-d').val('0');
        $('.discount-d').val('0');

        // set select currency corresponding elms
        let targetPriceElm = `.price_${selectedCurrency}`;
        let targetDiscountElm = `.discount_${selectedCurrency}`;

        let priceVal = $('#txt_price-d').val();
        let discountVal = $('#txt_discount-d').val();

        $(targetPriceElm).val(priceVal);
        $(targetDiscountElm).val(discountVal);
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
                        return $("#rb_is_course_paid-d").is(':checked')
                    }
                }
            },
            // price_usd: {
            //     required: {
            //         depends: function(element) {
            //             return $("#rb_is_course_paid-d").is(':checked')
            //         }
            //     },
            //     min: 1,
            // },
            // discount_usd: {
            //     // required: true,
            //     max: 100,
            //     min: 0,
            // },
            discount: {
                // required: true,
                max: 100,
                min: 0,
            },
            price: {
                required: {
                    depends: function(element) {
                        return $("#rb_is_course_paid-d").is(':checked')
                    }
                },
                min: 1,
            },
            // price_pkr: {
            //     required: {
            //         depends: function(element) {
            //             return $("#rb_is_course_paid-d").is(':checked')
            //         }
            //     },
            //     min: 1,
            // },
            // discount_pkr: {
            //     // required: true,
            //     max: 100,
            //     min: 0,
            // },
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
            price: {
                required: 'Price is Required'
            },
            // price_pkr: {
            //     required: 'Price (PKR) is Required'
            // },
            // price_usd: {
            //     required: 'Price (USD) is Required'
            // },
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
                            let course_uuid = response.data.uuid;
                            let teacher_uuid = response.data.teacher.uuid;
                            $('.hdn_course_uuid-d').val(course_uuid).attr('value', course_uuid);
                            $('#hdn_teacher_uuid-d').val(teacher_uuid).attr('value', teacher_uuid);
                            $('.nav_item_trigger_link-d').removeClass('disabled');

                            switchModal('course_details_modal-d', 'waiting_popup-d');
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
        let elm = $(this);
        let form = '';
        if ($(elm).parents('.main_page-d').length < 1) {
            form = $(elm).parents('.flex-sm-column-reverse').find('#course_outline_form-d');
        } else {
            form = $('#add_outline').find('#course_outline_form-d');
            if ($('#frm_course_details-d').length < 1) {
                if ($('#hdn_uuid-d').length < 1) {
                    let course_uuid = $('.course_detail_title_heading-d').attr('data-uuid');
                    let course_uuid_elm = "<input type='hidden' name='course_uuid' id='hdn_uuid-d' value='" + course_uuid + "' />"
                    $(form).append(course_uuid_elm);
                }
            }
            $('#add_outline').modal('show');
        }
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
        let total_count = $("#total_outline-d").text();

        var removeOutline = function() {
            $(container).remove();
            total_count = total_count - 1;
            $("#total_outline-d").text(total_count);
            $("#total_outline_count-d").text(total_count);
            let form = $('#course_outline_form-d');
            if ($(form).length > 0) {
                let hdnField = $(form).find('#hdn_course_outline-d');
                if ($(hdnField).length > 0) {
                    if ($(hdnField).val().trim() == uuid) {
                        $(hdnField).val('').attr('value', '');
                    }
                }
            }
        }
        modelName = 'Outline';
        targetUrl = modal_delete_outline_url;
        postData = { course_outline_uuid: uuid };
        deleteRecord(targetUrl, postData, removeOutline, 'removeOutline', modelName);
    });

    // setup min value for minutes field based hrs field
    $('#course_outline_form-d').on('change', '#duration_hrs-d', function(e) {
        let elm = $(this);
        if ($(elm).val() != '') {
            let hrsVal = $(elm).val();
            if (isNaN(hrsVal)) {
                errorAlert('Please insert proper number');
                return false;
            } else {
                if (parseInt(hrsVal) > 0) {
                    $('#duration_mins-d').attr('min', 0);
                } else {
                    $('#duration_mins-d').attr('min', 1);
                }
            }
        }
    });
    // validate and submit form
    $('#course_outline_form-d').validate({
        ignore: ".ignore",
        rules: {
            duration_hrs: {
                required: true,
                min: 0,
                max: 23
            },
            duration_mins: {
                required: {
                    depends: function(element) {
                        return ($("#duration_hrs-d").val() != '');
                    }
                },
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
                max: "Hour value cannot exceed 23",
            },
            duration_mins: {
                required: "Minutes value is Required",
                min: "Duration MUST be greater than {0}",
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
                            existingElm = $('.uuid_' + model.uuid);
                            if ($(existingElm).length > 0) {
                                debugger;
                                $(existingElm).find('.outline_title-d').text(model.title);
                                $(existingElm).find('.outline_duration-d').text(model.duration_hrs + ':' + model.duration_mins + ' Hrs');
                                $(existingElm).find('.course_outline_uuid-d').val(model.uuid).attr('value', model.uuid);
                            } else {
                                if ($('#cloneable_outline-d').length > 0) {
                                    let clonedElm = $('#cloneable_outline-d').clone();
                                    $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                    $(clonedElm).find('.outline_title-d').text(model.title);
                                    $(clonedElm).find('.outline_duration-d').text(model.duration_hrs + ':' + model.duration_mins + ' Hrs');
                                    $(clonedElm).find('.course_outline_uuid-d').val(model.uuid).attr('value', model.uuid);
                                    $(".outlines_container-d").append(clonedElm);
                                    $(".outlines_container-d").find('.outline_serial-d').each(function(i, elm) {
                                        console.log(i);
                                        $(elm).text(i + 1);
                                        $("#total_outline-d").text(i);
                                        $("#total_outline_count-d").text(i);
                                    });
                                    $(".outlines_container-d").find('.no_item_container-d').remove(); // remove no records container
                                }
                            }
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

    // Open add course outline Modal
    $('#outline_main_container-d').on('click', '.open_add_outline_modal-d', function(e) {
        let modal = $('#add_outline');
        let form = $(modal).find('form');
        resetOutlineForm(form);
        $(modal).modal('show');
    });

    /**
     * Reset Outline form
     * @param {DomElm} form
     */
    function resetOutlineForm(form) {
        $(form).find('#outline_title-d').val('').attr('value', '');
        $(form).find('#duration_hrs-d').val('').attr('value', '');
        $(form).find('#duration_mins-d').val('').attr('value', '');
        $(form).find('#hdn_course_outline-d').val('').attr('value', '');
    }


    // video course conetnt - START
    $('#video_course_content_form-d').on('click', '#trigger_video_course_upload-d', function(e) {
        $(this).parents('.file-loading').find('#upload_course_content-d').trigger('click');
    });

    // working on edit of course content
    $('.video_course_content_container-d').on('click', '.edit_video_content-d', function(e) {
        let elm = $(this);
        let form = $('#video_course_content_form-d');

        if ($(elm).parents('.videos_main_container-d').length > 0) {
            $('#add_video_modal').modal('show');
            $(form).parents('modal').find('.video_course_content_container-d').html('');
        }

        let container = $(elm).parents('.video_course_single_container-d');
        let thumbnailSrcLink = $(container).find('.video_course_content_thumbnail-d').attr('src');
        let image_path = thumbnailSrcLink.replace(UPLOAD_URL + '/', '');
        let link = $(container).find('.video_course_link-d').attr('href');
        let title = $(container).find('.video_course_title-d').text().trim();
        let duration = $(container).find('.video_course_duration-d').text();
        duration = duration.replace(' Hrs', '').split(':');
        let uuid = $(container).find('.course_video_uuid-d').val();

        $(form).find('#video_course_url_link-d').val(link).attr('value', link);
        $(form).find('#video_course_title-d').val(title).attr('value', title);
        $(form).find('#content_image-d').attr('src', thumbnailSrcLink);
        $(form).find('#hdn_content_image-d').val(image_path).attr('value', image_path);
        // console.log($(form).find('#hdn_content_image-d').val());
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

            let form = $('#video_course_content_form-d');
            if ($(form).length > 0) {
                let hdnField = $(form).find('#hdn_video_course_content_uuid-d');
                if ($(hdnField).length > 0) {
                    if ($(hdnField).val().trim() == uuid) {
                        $(hdnField).val('').attr('value', '');
                    }
                }
            }
        }

        modelName = 'Course Video';
        targetUrl = modal_delete_video_content_url;
        postData = { course_content_uuid: uuid };
        deleteRecord(targetUrl, postData, removeCourseContent, 'removeCourseContent', modelName);
    });

    // setup min value for minutes field based hrs field
    $('#video_course_content_form-d').on('change', '#content_duration_hrs-d', function(e) {
        let elm = $(this);
        if ($(elm).val() != '') {
            let hrsVal = $(elm).val();
            if (isNaN(hrsVal)) {
                errorAlert('Please insert proper number');
                return false;
            } else {
                if (parseInt(hrsVal) > 0) {
                    $('#content_duration_mins-d').attr('min', 0);
                } else {
                    $('#content_duration_mins-d').attr('min', 1);
                }
            }
        }
    });

    // video course content form processing and validation
    $('#video_course_content_form-d').validate({
        ignore: ".ignore",
        rules: {
            content_title: {
                required: true,
                minlength: 5,
            },
            duration_hrs: {
                required: true,
                min: 0,
                max: 23
            },
            duration_mins: {
                required: true,
                // min: 10,
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
                required: "Hours Value is Required.",
                min: "Hour must be a Non-Negetive number",
                max: "Hour value cannot exceed 23",
            },
            duration_mins: {
                required: "Minutes value is Required.",
                min: "Duration MUST be greater than {0} minutes",
                max: "Minutes value cannot be more than 59",
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
                            existingElm = $('.uuid_' + model.uuid);
                            let clonedElm;
                            if ($(existingElm).length > 0) {
                                clonedElm = existingElm;
                            } else {
                                if ($('#cloneable_video_course_content-d').length > 0) {
                                    clonedElm = $('#cloneable_video_course_content-d').clone();
                                    $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                } else {
                                    console.log('element to clone could not be found for Content')
                                    return false;
                                }
                            }

                            if (model.content_image.includes(ASSET_URL, 0)) {
                                $(clonedElm).find('.video_course_content_thumbnail-d').attr('src', model.content_image);
                            } else {
                                $(clonedElm).find('.video_course_content_thumbnail-d').attr('src', UPLOAD_URL + '/' + model.content_image);
                            }
                            // $(clonedElm).find('.video_course_content_thumbnail-d').attr('src', model.content_image);
                            $(clonedElm).find('.video_course_title-d').text(model.title.trim());
                            $(clonedElm).find('.video_course_link-d').attr('href', model.url_link);
                            $(clonedElm).find('.video_course_duration-d').text(model.duration_hrs + ':' + model.duration_mins + ' Hrs');
                            $(clonedElm).find('.course_video_uuid-d').val(model.uuid).attr('value', model.uuid);

                            if (($(existingElm).length < 1) && ($('#cloneable_video_course_content-d').length > 0)) {
                                $(".video_course_content_container-d").append(clonedElm);
                                // $('.no_item_container-d').remove(); // remove no records container
                            }
                            resetContentForm(form);
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

    // video form reset
    $('#video_course_content_form-d').on('click', '.reset_form-d', function(e) {
        resetContentForm($(this).parents('form'));
    });

    // update video content
    $('.video_course_content_container-d').on('click', '.play_video_in_modal-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.video_course_single_container-d');
        let targetVideoUrl = $(container).find('.video_course_link-d').attr('href');

        if (targetVideoUrl.includes('youtube')) {

            targetVideoUrl = targetVideoUrl.replace('watch?v=', 'embed/');
            targetVideoUrl = targetVideoUrl.replace('&', '?');
            // targetVideoUrl = targetVideoUrl.replace('&ab_channel', '?ab_channel');
            console.log(targetVideoUrl);
        }
        $('#open_video_modal-d').find('.iframe_play_video-d').attr('src', targetVideoUrl);
        $('#open_video_modal-d').modal('show');
    });

    // open video modal
    $('#open_video_modal-d').on('hidden.bs.modal', function() {
        $(this).find('.iframe_play_video-d').attr('src', '');
    });

    /**
     * Reset Content Form
     *
     * @param DomElement form
     */
    function resetContentForm(form) {
        $(form).find('#video_course_title-d').val('').attr('value', '');
        $(form).find('#content_duration_hrs-d').val('').attr('value', '');
        $(form).find('#content_duration_mins-d').val('').attr('value', '');
        $(form).find('#video_course_url_link-d').val('').attr('value', '');

        $(form).find('#upload_course_content-d').val('').attr('value', '');
        $(form).find('#hdn_content_image-d').val('').attr('value', '');
        $(form).find('#hdn_video_course_content_uuid-d').val('').attr('value', '');

        let defaultPreviewImage = $(form).find('.preview_img').attr('data-default_path');
        $(form).find('.preview_img').attr('src', defaultPreviewImage);
    }

    // video course conetnt - END




    // course slot

    // toggle day num selection in form
    $('.course_slots_form-d').on('click', '.slot_day-d', function(e) {
        let elm = $(this);
        let formContainer = $(elm).parents('.course_slots_form-d');
        let day_num = $(elm).attr('data-day_num');
        let input_day_nums = $(formContainer).find('#course_slot_selected_days-d');
        let selected_slots = $(input_day_nums).val().trim();

        selected_slots = addUpdateCommaSeperatedString(selected_slots, day_num);
        $(input_day_nums).val(selected_slots).attr('value', selected_slots);
        $(elm).toggleClass('custom_day_sign_active-s', 'custom_day_sign-s')
    });

    // validate and submit slots form
    $('#course_slots_form-d').validate({
        ignore: ".ignore",
        rules: {
            start_date: {
                required: true,
                // min: 1,
            },
            end_date: {
                required: true,
                // min: 1,
            },
            start_time: {
                required: true,
                // min: 1,
            },
            end_time: {
                required: true,
                // min: 0,
            },
            day_nums: {
                required: true,
                // min: 1,
            },
        },
        messages: {
            start_date: {
                required: "Start Date is Required",
                // min: "Date Should have atleast 1 characters",
            },
            end_date: {
                required: "End Date is Required",
                // min: "Date Should Have atleast 1",
            },
            start_time: {
                required: "Start Time is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            end_time: {
                required: "End Time is Required.",
                // minlength: "Time Should have atleast 1 characters",
            },
            day_nums: {
                required: 'Select at-least 1 day for slot',
                // min: 1,
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
                            let count_slot = $("#total_slot_count-d").text();

                            existingElm = $('.uuid_' + model.uuid);
                            let clonedElm;
                            if ($(existingElm).length > 0) {
                                clonedElm = existingElm;

                                // console.log(model);

                            } else {
                                if ($('#cloneable_coourse_slot_container-d').length > 0) {
                                    clonedElm = $('#cloneable_coourse_slot_container-d').clone();
                                    $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                } else {
                                    console.log('element to clone could not be found for slots')
                                    return false;
                                }
                            }

                            $(clonedElm).find('.slot_start_date-d').text(model.model_start_date).attr('data-course_start_date', model.course.model_start_date).attr('data-slot_start_date', model.model_start_date_php);
                            $(clonedElm).find('.slot_start_time-d').text(model.model_start_time).attr('data-slot_start_time', model.model_start_time_php);
                            $(clonedElm).find('.slot_end_date-d').text(model.model_end_date).attr('data-course_end_date', model.course.model_end_date).attr('data-slot_end_date', model.model_end_date_php);
                            $(clonedElm).find('.slot_end_time-d').text(model.model_end_time).attr('data-slot_end_time', model.model_end_time_php);

                            $(clonedElm).find('.course_slot_uuid-d').val(model.uuid).attr('value', model.uuid);
                            $(clonedElm).find('.listing_course_day_nums-d').val(model.day_nums).attr('value', model.day_nums);

                            // let selected_day_numbs = model.day_nums.split(',');
                            $(clonedElm).find('.slot_day-d').each(function(indexInArray, slotElm) {
                                let elmDayNumb = $(slotElm).attr('data-day_num');
                                if (model.day_nums.indexOf(elmDayNumb) > -1) {
                                    $(slotElm).removeClass('custom_day_sign-s').addClass('custom_day_sign_active-s');
                                } else {
                                    $(slotElm).removeClass('custom_day_sign_active-s').addClass('custom_day_sign-s');
                                }
                            });

                            if (($(existingElm).length < 1) && ($('#cloneable_coourse_slot_container-d').length > 0)) {
                                count_slot++;
                                $(".total_count_slots-d").text(count_slot);
                                $(".slots_container-d").append(clonedElm);
                            }
                            resetSlotForm(form)
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

    // edit a slot
    $('.slots_container-d').on('click', '.edit_slot-d', function(e) {
        let form = '';

        let elm = $(this);
        form = $('#course_slots_form-d');

        let container = $(elm).parents('.single_slot_container-d');
        let uuid = $(container).find('.course_slot_uuid-d').val();

        let start_date = $(container).find('.slot_start_date-d').attr('data-slot_start_date');
        let start_time = $(container).find('.slot_start_time-d').attr('data-slot_start_time');
        let end_date = $(container).find('.slot_end_date-d').attr('data-slot_end_date');
        let end_time = $(container).find('.slot_end_time-d').attr('data-slot_end_time');

        let course_start_date = $(container).find('.slot_start_date-d').attr('data-course_start_date');
        let course_end_date = $(container).find('.slot_end_date-d').attr('data-course_end_date');

        let selected_day_nums = $(container).find('.listing_course_day_nums-d').val().trim();

        $(form).find('#course_slot_uuid-d').val(uuid).attr('value', uuid);

        if ($(elm).parents('.slots_main_container-d').length > 0) {
            $('#add_slot_modal').modal('show');
            $(form).parents('modal').find('.slots_container-d').html('');
        }

        // form = $('#add_slot_modal').find('#course_slots_form-d-d');
        // if ($('#frm_course_details-d').length < 1) {
        //     if ($('#hdn_uuid-d').length < 1) {
        //         let course_uuid = $('.course_detail_title_heading-d').attr('data-uuid');
        //         let course_uuid_elm = "<input type='hidden' name='course_uuid' id='hdn_uuid-d' value='" + course_uuid + "' />"
        //         $(form).append(course_uuid_elm);
        //     }
        // }
        // $('#add_slot_modal').modal('show');

        $(form).find('#course_slot_start_date-d').val(start_date).attr('value', start_date).attr('min', course_start_date).attr('max', course_end_date);
        $(form).find('#course_slot_start_time-d').val(start_time).attr('value', start_time);
        $(form).find('#course_slot_end_date-d').val(end_date).attr('value', end_date).attr('min', course_start_date).attr('max', course_end_date);;
        $(form).find('#course_slot_end_time-d').val(end_time).attr('value', end_time);

        $(form).find('#course_slot_selected_days-d').val(selected_day_nums).attr('value', selected_day_nums);
        $(form).find('.slot_day-d').each(function(indexInArray, slotElm) {
            let elmDayNumb = $(slotElm).attr('data-day_num');
            if (selected_day_nums.indexOf(elmDayNumb) > -1) {
                $(slotElm).removeClass('custom_day_sign-s').addClass('custom_day_sign_active-s');
            } else {
                $(slotElm).removeClass('custom_day_sign_active-s').addClass('custom_day_sign-s');
            }
        });
    });

    // delete a slot of course
    $('.slots_container-d').on('click', '.delete_slot-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.single_slot_container-d');
        let uuid = $(container).find('.course_slot_uuid-d').val();
        let total_count_slot = $("#total_slot_count-d").text();
        console.log(total_count_slot);
        // console.log(uuid);
        var removeSlot = function() {
            $(container).remove();
            total_count_slot = total_count_slot - 1;
            $("#total_slot_count-d").text(total_count_slot);
            $('.course_slot_count-d').text(total_count_slot);

            let form = $('#course_slots_form-d');
            if ($(form).length > 0) {
                let hdnField = $(form).find('#course_slot_uuid-d');
                if ($(hdnField).length > 0) {
                    if ($(hdnField).val().trim() == uuid) {
                        $(hdnField).val('').attr('value', '');
                    }
                }
            }
        }
        modelName = 'Slot';
        targetUrl = modal_delete_slot_url;
        postData = { course_slot_uuid: uuid };
        deleteRecord(targetUrl, postData, removeSlot, 'removeSlot', modelName);
    });

    // Open add course outline Modal
    $('#course_slot_main_container-d').on('click', '.open_add_slot_modal-d', function(e) {
        let min_date = $("#date_range-d").attr('data-start_date');
        let max_date = $("#date_range-d").attr('data-end_date');
        let modal = $('#add_slot_modal');
        let form = $(modal).find('form');
        form.find()
        form.find('#course_slot_start_date-d').attr('min', min_date).attr('max', max_date);
        form.find('#course_slot_end_date-d').attr('min', min_date).attr('max', max_date);

        resetSlotForm(form);
        $(modal).modal('show');
    });

    /**
     * Reset Slot form
     * @param {DomElm} form
     */
    function resetSlotForm(form) {
        $(form).find('#course_slot_start_date-d').val('').attr('value', '');
        $(form).find('#course_slot_end_date-d').val('').attr('value', '');
        $(form).find('#course_slot_start_time-d').val('').attr('value', '');
        $(form).find('#course_slot_end_time-d').val('').attr('value', '');

        $(form).find('#course_slot_selected_days-d').val('').attr('value', '');
        $(form).find('#course_slot_start_time-d').val('').attr('value', '');
        $(form).find('#course_slot_end_time-d').val('').attr('value', '');
        $(form).find('#course_slot_uuid-d').val('').attr('value', '');
        $(form).find('.slot_day-d').removeClass('custom_day_sign_active-s').addClass('custom_day_sign-s');
    }


    // reset slot form
    $('#course_slots_form-d').on('click', '.reset_form-d', function(e) {
        resetSlotForm($(this).parents('form'));
    });

    // course details modal - END

    // click stats container and show relavent content
    $('#course_details_stats_container-d').on('click', '.course_stats-d', function(e) {
        $('.course_stats-d').removeClass('active');
        let elm = $(this);
        $(elm).addClass('active');

        let targetElm = '#' + $(elm).attr('data-target_elm');
        // console.log(targetElm);
        $('.course_details_container-d').hide();
        $(targetElm).show();
    });



    // course setting page - START

    // trigger file upload
    $('#frm_course_setting-d').on('click', '.click_course_image-d', function(e) {
        $('#upload_course_image-d').trigger('click');
    });

    // validate and sumit form
    $('#frm_course_setting-d').on('click', '.course_status-d', function(e) {
        let elm = $(this);
        let status = $(elm).attr('data-status');
        let container = $(this).parents('form');
        $('.course_status-d').removeClass('active');
        $(elm).addClass('active');
        $(container).find('#hdn_course_status-d').val(status).attr('value', status);
    });

    // course fee - START

    // // hide elms by default
    // $('#handout_section_setting-d').hide();
    // $('#course_detail_setting-d').hide();

    // // show|hide fee section  based on if course if free
    // $('#course_setting_is_paid-d').on('click', '.rb_course_free-d', function(e) {
    //     let is_course_free = $(this).attr('value');
    //     if ('1' == is_course_free) {
    //         $('#handout_section_setting-d').hide();
    //         $('#course_detail_setting-d').hide();
    //     } else {
    //         $('#handout_section_setting-d').show();
    //         $('#course_detail_setting-d').show();
    //     }
    // });









    // course setting form
    $('#frm_course_setting-d').validate({
        ignore: ".ignore",
        rules: {
            course_image: {
                required: true
            },
            title: {
                required: true,
                minlength: 3,
            },
            course_status: {
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
            content_title: {
                required: "Title is Required",
                minlength: "Title Should have atleast 3 characters",
            },
            course_status: {
                required: "Course Status Required",
            },
            course_category_uuid: {
                required: "Category is Required",
            },
            description: {
                required: "Description is Required.",
            },
        },
        errorPlacement: function(error, element) {
            // console.log(error, element);
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
                            // window.location.href = APP_URL;
                            let course_uuid = response.data.uuid;
                            $('.course_uuid-d').val(course_uuid).attr('value', course_uuid);
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

    // show setting of course
    $('#show_course-setting-d').on('click', function(e) {
        let elm = $(this);
        let targetContainerId = '#' + $(elm).attr('data-target_elm');
        $('.course_stats-d').removeClass('active');
        $('.course_details_container-d').hide();
        $(targetContainerId).show();
    })

    // course setting page - END
    $('#course_handout_content_form-d').on('change', '#link-d', function(e) {
        let elm = $(this);
        let url = $(elm).val().trim();

        console.log(getMediaTypeByUrl(url));
        // console.log('hdfsjdf');
    });

    // course handout section - START
    $('#course_handout_content_form-d').validate({
        ignore: ".ignore",
        rules: {
            handout_title: {
                required: true,
                minlength: 5,
            },
            url_link: {
                required: true,
                url: true,
            },
            course_uuid: {
                required: true,
            },
        },
        messages: {
            handout_title: {
                required: "Title is Required",
                minlength: "Title Should have atleast 05 characters",
            },
            url_link: {
                required: "URL is Required.",
                url: 'URL link must be a valid URL',
            },
            course_uuid: {
                required: "Course UUID is required"
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
                            // get total course handout
                            let total_handouts = $("#total_handouts_count-d").text();

                            existingElm = $('.uuid_' + model.uuid);
                            let clonedElm;
                            if ($(existingElm).length > 0) {
                                clonedElm = existingElm;
                            } else {
                                if ($('#cloneable_course_handout_content-d').length > 0) {

                                    clonedElm = $('#cloneable_course_handout_content-d').clone();
                                    $(clonedElm).removeAttr('id').addClass('uuid_' + model.uuid);
                                    // col-xl-3 col-lg-3 col-md-4
                                    // determine container type and add above class in it
                                } else {
                                    console.log('element to clone could not be found for Content')
                                    return false;
                                }
                            }

                            // $(clonedElm).find('.video_course_content_thumbnail-d').attr('src', model.content_image);
                            $(clonedElm).find('.handout_title-d').text(model.title.trim());
                            $(clonedElm).find('.course_handout_link-d').attr('href', model.url_link);
                            $(clonedElm).find('.handout_uuid-d').val(model.uuid).attr('value', model.uuid);

                            if (($(existingElm).length < 1) && ($('#cloneable_course_handout_content-d').length > 0)) {

                                total_handouts++;
                                $(".total_course_handout-d").text(getPaddedString(total_handouts));
                                let model_title = model.title.trim();
                                let truncated_title = model_title;

                                // case: its a tab in add course modal
                                if ($('#nav_handout_content').length > 0) {
                                    let temp = $(clonedElm).clone();
                                    $(temp).removeClass('col-lg-3').removeClass('col-md-4');
                                    $(temp).addClass('col-lg-4').addClass('col-md-6');
                                    truncated_title = getTruncatedString(model_title, 10);
                                    $(temp).find('.handout_title-d').attr('data-title', model_title).text(truncated_title);
                                    $('#nav_handout_content').find('.course_handout_container-d').append(temp);
                                }

                                // case: its a modal popup in update course/view course page
                                if ($('#add_handout_modal').length > 0) {
                                    let temp2 = $(clonedElm).clone();
                                    $(temp2).removeClass('col-lg-3').removeClass('col-md-4');
                                    $(temp2).addClass('col-lg-4').addClass('col-md-6');
                                    truncated_title = getTruncatedString(model_title, 10);
                                    $(temp2).find('.handout_title-d').attr('data-title', model_title).text(truncated_title);
                                    $('#add_handout_modal').find('.course_handout_container-d').append(temp2);
                                }

                                // case: its a course view page
                                if ($('#handout_main_container-d').length > 0) {
                                    let temp3 = $(clonedElm).clone();

                                    $(temp3).addClass('col-lg-3').addClass('col-md-4');
                                    $(temp3).removeClass('col-lg-4').removeClass('col-md-6');
                                    truncated_title = getTruncatedString(model_title, 15);
                                    $(temp3).find('.handout_title-d').attr('data-title', model_title).text(truncated_title);
                                    $('#handout_main_container-d').find('.course_handout_container-d').append(temp3);
                                }



                                // if ($(".course_handout_container-d").parents('#add_handout_modal').length < 1) { // case: its parent is not modal popup
                                //     $(".course_handout_container-d").find('.course_handout_single_container-d').addClass('col-md-4');
                                // }
                                // $('.no_item_container-d').remove(); // remove no records container
                            }
                            resetHandoutForm(form);
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

    $('#course_handout_content_form-d').on('click', '.reset_form-d', function(e) {
        resetHandoutForm($(this).parents('form'));
    });

    // delete a slot of course
    $('.course_handout_container-d').on('click', '.delete_handout_content-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.course_handout_single_container-d');
        let uuid = $(container).find('.handout_uuid-d').val();
        let total_handout_count = $("#total_handouts_count-d").text();
        // console.log(uuid);
        var removeHandout = function() {
            $(container).remove();
            total_handout_count = total_handout_count - 1;
            $("#total_handouts_count-d").text(total_handout_count);
            $("#total_handout_count-d").text(total_handout_count);

            let form = $('#course_handout_content_form-d');
            if ($(form).length > 0) {
                let hdnField = $(form).find('#hdn_handout_content_uuid-d');
                if ($(hdnField).length > 0) {
                    if ($(hdnField).val().trim() == uuid) {
                        $(hdnField).val('').attr('value', '');
                    }
                }
            }
        }
        modelName = 'Handout';
        targetUrl = modal_delete_handout_url;
        postData = { handout_content_uuid: uuid };
        deleteRecord(targetUrl, postData, removeHandout, 'removeHandout', modelName);
    });

    $('.course_handout_container-d').on('click', '.edit_handout_content-d', function(e) {
        let form = '';

        let elm = $(this);
        form = $('#course_handout_content_form-d');

        let container = $(elm).parents('.course_handout_single_container-d');
        let uuid = $(container).find('.handout_uuid-d').val();

        let title = $(container).find('.handout_title-d').attr('data-title');
        let link = $(container).find('.course_handout_link-d').attr('href');

        $(form).find('#hdn_handout_content_uuid-d').val(uuid).attr('value', uuid);

        if ($(elm).parents('.course_handout_container-d').length > 0) {
            $('#add_handout_modal').modal('show');
            $(form).parents('modal').find('.course_handout_container-d').html('');
        }

        $(form).find('#handout-d').val(title).attr('value', title);
        $(form).find('#link-d').val(link).attr('value', link);
        $(form).find('#hdn_handout_content_uuid-d').val(uuid).attr('value', uuid);
    });

    /**
     * Reset Handout Form
     *
     * @param {DomElement} form
     */
    function resetHandoutForm(form) {
        $(form).find('#handout-d').val('').attr('value', '');
        $(form).find('#link-d').val('').attr('value', '');
        $(form).find('#hdn_handout_content_uuid-d').val('').attr('value', '');
        $(form).find('#hdn_course_handout_content_uuid-d').val('').attr('value', '');

        let defaultPreviewImage = $(form).find('.preview_img').attr('data-default_path');
        $(form).find('.preview_img').attr('src', defaultPreviewImage);
    }
    // course hadnout section - END

    $('#open_course_queries_modal-d').on('click', function(e) {
        $('#course_queries_modal-d').modal('show');
    });

    $(".frm_respond_query-d").each(function() {
        $(this).validate({

            ignore: ".ignore",
            rules: {
                response_body: {
                    required: true,
                    minlength: 5,
                },
            },
            messages: {
                response_body: {
                    required: "Response Body is Required",
                    minlength: "Response Body Should have atleast 05 characters",
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
                var form_data = current_form;

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

                                $(form).parents('.new_answer_container-d').hide();
                                $(form).find('.query_response_uuid-d').val(model.uuid).attr('value', model.uuid);
                                let workContainer = $(form).parents('.single_course_query_container-d').find('.manage_answer_container-d');
                                $(workContainer).find('.teacher_answer-d').text(model.body);
                                $(workContainer).find('.query_response_uuid-d').val(model.uuid).attr('value', model.uuid);
                                $(workContainer).find('.query_response_uuid-d').val(model.uuid).attr('value', model.uuid);
                                $(workContainer).show();

                                resetStudentQueryForm(form);
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
        })
    });

    function resetStudentQueryForm(form) {
        // do nothing
        // $(form).find('.response_body-d').val('').text('');
    }

    // edit a query response
    $(".single_course_query_container-d").on('click', '.edit_query_response-d', function(e) {
        let elm = $(this);
        let mainConatiner = $(elm).parents('.single_course_query_container-d');
        $(mainConatiner).find('.manage_answer_container-d').hide();
        $(mainConatiner).find('.new_answer_container-d').show();
    });

    // delete a query response
    $(".single_course_query_container-d").on('click', '.delete_query_response-d', function(e) {
        let elm = $(this);
        let mainConatiner = $(elm).parents('.single_course_query_container-d');
        let uuid = $(mainConatiner).find('.query_response_uuid-d').val();

        var removeQueryResponse = function() {
            $(mainConatiner).find('.manage_answer_container-d').hide();
            $(mainConatiner).find('.new_answer_container-d').show();
            $(mainConatiner).find('.query_response_uuid-d').val('').attr('value', '');
            $(mainConatiner).find('.response_body-d').val('').attr('value', '').text('');
        }
        modelName = 'Response';
        targetUrl = modal_delete_query_response_url;
        postData = { query_response_uuid: uuid };

        deleteRecord(targetUrl, postData, removeQueryResponse, 'removeQueryResponse', modelName);
    });

    // show|hide card based on payment option selection
    $('#enroll_student_modal-d').on('change', '.ddl_pay_method-d', function(e) {
        let elm = $(this);
        if ($(elm).val() == 'stripe') {
            $('.stripe_cards_container-d').show();
        } else {
            $('.stripe_cards_container-d').hide();
        }
    })

    // mark a slot as selected
    $('#enroll_student_modal-d').on('click', '.slot_option-d', function(e) {
        let elm = $(this);
        $('.slot_option-d').removeClass('bg_success-s').addClass('bg_light_dark-s');
        let modal = $(elm).parents('.modal');
        $(elm).removeClass('bg_light_dark-s').addClass('bg_success-s');
        let slot_uuid = $(elm).attr('data-slot_option_uuid');
        $(modal).find('.hdn_modal_slot_uuid-d').val(slot_uuid);
    });

    // pen enrol student popup from courses grid
    $('body').on('click', '.setup_enroll_student_modal-d', function(e) {

        let elm = $(this);
        let uuid = $(elm).attr('data-course_uuid');
        let target_url = $(elm).attr('data-target_url');
        $.ajax({
            url: target_url,
            type: 'POST',
            dataType: 'json',
            data: { course_uuid: uuid, get_slots_html:1 },
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                if (response.status) {
                    let model = response.data;
                    let modal = $('#enroll_student_modal-d');
                    // let form = $(modal).find('.frm_confirm_enrollment-d');
                    // $(form).find('.course_title-d').text()

                    $(modal).find('.course_title-d').text(model.title);
                    $(modal).find('.course_status-d').text(model.status);
                    $(modal).find('.modal_course_joining_date-d').attr('min', model.start_date).attr('max', model.end_date);

                    $(modal).find('.modal_amount_payable-d').val(getCoursePriceOnly(model)).attr('min', getCoursePriceOnly(model));
                    $(modal).find('.modal_amount_payable-d').attr('readonly', 'readonly');
                    $(modal).find('.hdn_modal_is_course_free-d').val(model.is_course_free);
                    $(modal).find('.hdn_modal_course_nature-d').val(model.nature);

                    $(modal).find('.course_slots_main_container-d').html(model.slots_view);

                    if (model.is_course_free == 1) {
                        $(modal).find('.payment_method_conatiner-d').hide();
                        $(modal).find('.fee_amount_container-d').hide();
                        $(modal).find('.btn_confirm_enrollment-d').replaceWith('<button class="btn btn-success btn_success btn_confirm_enrollment-d" role="button" type="button" data-dismiss="modal"> Close </button>');
                    } else {
                        $(modal).find('.payment_method_conatiner-d').show();
                        $(modal).find('.fee_amount_container-d').show();
                        if(model.my_enrollment_count > 0){
                            $(modal).find('.btn_confirm_enrollment-d').replaceWith('<button class="btn btn-success btn_success btn_confirm_enrollment-d" role="button" type="button" data-dismiss="modal"> Close </button>');
                        }
                        else{
                            $(modal).find('.btn_confirm_enrollment-d').replaceWith('<button class="btn btn-success btn_success btn_confirm_enrollment-d" role="button" type="submit"> Enroll </button>');
                        }
                    }


                    // <button class='btn btn-success btn_success btn_confirm_enrollment-d' role="button" @if((isset($course) && ($course->available_slots_count)) || (isset($course) && $course->nature == 'video')) type="submit" @else type="button" data-dismiss="modal" @endif>@if((isset($course) && ($course->available_slots_count)) || (isset($course) && $course->nature == 'video')) Enroll @else Close @endif</button>

                    $(modal).find('.hdn_modal_course_uuid-d').val(model.uuid);

                    $(modal).modal('show');

                    console.log(model);
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

    // popup enrol modal for when requested for enrollment
    $('body').on('click', '.enroll_student-d', function(e) {
        let elm = $(this);
        let selection = $(elm).attr('data-course_uuid');
        let data = { 'course_uuid': selection };

        $.ajax({
            url: get_course_slots_by_course_uuid_url,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                if (response.status) {
                    let model = response.data;
                    let modal = $('#enroll_student_modal-d');
                    $(modal).find('.course_title-d').text(model.title);
                    $(modal).find('.course_status-d').text(model.status);
                    $(modal).find('.modal_course_joining_date-d').attr('min', model.start_date).attr('max', model.end_date);

                    $(modal).find('.modal_amount_payable-d').val(getCoursePriceOnly(model)).attr('min', getCoursePriceOnly(model));
                    $(modal).find('.hdn_modal_is_course_free-d').val(model.is_course_free);
                    $(modal).find('.hdn_modal_course_nature-d').val(model.nature);

                    $(modal).find('.course_slots_main_container-d').html(model.slots_view);

                    if (model.is_course_free == 1) {
                        $(modal).find('.payment_method_conatiner-d').hide();
                        $(modal).find('.fee_amount_container-d').hide();
                    } else {
                        $(modal).find('.payment_method_conatiner-d').show();
                        $(modal).find('.fee_amount_container-d').show();
                    }

                    $(modal).find('.hdn_modal_course_uuid-d').val(model.uuid);
                    $(modal).modal('show');

                    console.log(response);
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

    // subbmit slot_uuid
    $('.frm_confirm_enrollment-d').validate({
        ignore: ".ignore",
        rules: {
            joining_date: {
                required: true,
            },
            amount: {
                required: {
                    depends: function(element) {
                        return ($('.hdn_modal_is_course_free-d').val() == '0')
                    }
                }
            },
            payment_method: {
                required: {
                    depends: function(element) {
                        return ($('.hdn_modal_is_course_free-d').val() == '0')
                    }
                }
            },
            card_uuid: {
                required: {
                    depends: function(element) {
                        return ($('.ddl_pay_method-d').val() == 'stripe')
                    }
                }
            },
            slot_uuid: {
                required: {
                    depends: function(element) {
                        return ($('.hdn_modal_course_nature-d').val() == 'online')
                    }
                }
            },
        },
        messages: {
            joining_date: {
                required: "Joining Date is Required",
                min: "Joining Date Must Exceed :min",
                max: "Joining Date Must be less than :max",
            },
            amount: {
                required: "Amount is Required.",
            },
            payment_method: {
                required: "Payment Method is required"
            },
            card_uuid: {
                required: "Card is required"
            },
            slot_uuid: {
                required: "Slot is required"
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
            var form_data = current_form;

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
                            window.location.reload();
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
                        text: xhr.responseJSON.message,
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

    $('.course_details_container-d').on('click', '.start_quiz-d', function(e) {
        let elm = $(this);
        let targetUrl = $(elm).attr('data-quiz_url');
        let modal = $('#confirmation_modal-d');
        $(modal).find('#start_test_quiz-d').attr('href', targetUrl);
        $(modal).modal('show');
    });

    let accepted_key_codes = getAcceptedKeyCodes();
    let ignored_key_codes = getIgnoredKeyCodes();
    // $('.dashboard_search-d').on('keydown', function(e) {
    //     let elm = $(this);
    //     let keywords = $(elm).val().trim();
    //     if (keywords.length > 3) {
    //         if (ignored_key_codes.includes(e.keyCode) == true) {
    //             e.stopPropagation();
    //             return false;
    //         }
    //     }
    // });

    // click anywhere in dom and it will hide the search container
    $('#page-content-wrapper').on('click', function(e) {
        let elm = $(this);
        if ($(elm).find('.dashboard_search-d')) { // confirm its a student side dashboard|search page
            if ($(e.target).hasClass('dashboard_search-d') || $(e.target).parents('.search_dropdown-d').length > 0) { // clicked on search related area
                // do nothing
            } else {
                $(elm).find('.search_dropdown-d').find('#search_ref_option-d').hide();
            }
        }
    });

    $('.dashboard_search-d').on('keyup', function(e) {
        let elm = $(this);
        let keywords = $(elm).val().trim();
        if (keywords.length > 3) {
            if (ignored_key_codes.includes(e.keyCode) == true) {
                return false;
            }
            $(elm).blur();
            if (accepted_key_codes.includes(e.keyCode) == true) {
                $.ajax({
                    url: search_Result_url,
                    type: 'POST',
                    dataType: 'json',
                    data: { keywords: $(elm).val().trim() },
                    beforeSend: function() {
                        showPreLoader();
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status) {
                            let href = $('.see_all_link-d').attr('href');
                            var r = new URL(href);
                            r.searchParams.delete('keywords');
                            r.searchParams.set('keywords', response.data.requestForm.keywords);

                            $('.see_all_link-d').attr('href', r.href).attr('data-keywords', response.data.requestForm.keywords).text(response.data.requestForm.keywords);

                            let models = response.data.courses;
                            if (models.length > 0) {
                                if ($('.course_search_results_container-d').length > 0) {
                                    $('.course_search_results_container-d').html('');
                                    $.each(models, function(i, model) {
                                        if ($('#cloneable_single_search_result-d').length > 0) {
                                            let clonedElm = $('#cloneable_single_search_result-d').clone();
                                            $(clonedElm).find('.search_Result_cat-d').text(model.category.name).attr('data-cat_uuid', model.category.uuid);
                                            let linkElm = $(clonedElm).find('.search_result_title_link-d');
                                            let targetUrl = preview_course_url;
                                            targetUrl = preview_course_url.replace('______', model.uuid);
                                            $(linkElm).text(model.title).attr('data-uuid', model.uuid).attr('href', targetUrl);
                                            $('.course_search_results_container-d').append(clonedElm);
                                        }
                                    });

                                    $('#search_ref_option-d').show();
                                }
                            } else {
                                $('.course_search_results_container-d').html('');
                                $('#search_ref_option-d').show();
                            }
                            // let result = response.data.courses;
                            // console.log(result);
                            // let showResult = $(".getResult");
                            // showResult.empty();
                            // $.each(result, function(i, e) {
                            //     console.log(e.title);
                            //     showResult.append(`${e.title}<br>`);
                            // });

                            // $(".show-result").removeClass('d-none');
                            // Swal.fire({
                            //     title: 'Success',
                            //     text: response.message,
                            //     icon: 'success',
                            //     showConfirmButton: false,
                            //     timer: 2000
                            // }).then((result) => {
                            //     // window.location.reload();
                            //     let models = response.data.courses;
                            //     // console.log(result);
                            //     let showResult = $(".getResult");
                            //     showResult.empty();
                            //     $.each(models, function(i, e) {
                            //         console.log(e.title);
                            //         showResult.append(`${e.title}<br>`);
                            //     });

                            //     $(".show-result").removeClass('d-none');
                            // });
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
            }
            // console.log('search: ', keywords, e.keyCode);

        }
        // ignore the rest
    });


    // open model for add Review
    $(".course_details_container-d").on('click', '#add_review-d', function(e) {
        let modal = $("#add_comment-d");
        let form = $(modal).find('#add_review_post-d');
        $(form).find('.txt_review_body-d').val('').text('');
        $(form).find('.hdn_review_uuid-d').val('');


        $(form).find('.get_rating-d').val(1);
        $(form).find('.rating-item').each(function(i, elm) {
            if ($(elm).attr('data-rate') == 1) {
                $(elm).addClass('active');
            } else {
                $(elm).removeClass('active');
            }
        });

        $(modal).modal('show');
    });


    // star rating value
    $('.star_rating-d').on('click', '.rating-d', function(e) {
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
                let rating_value = e.target.getAttribute("data-rate");
                $(".get_rating-d").val(rating_value);
                elClass.add('active'); // add active class to the clicked star
            }
        };


    });

    // add comment against the star rating
    // add Question
    $('#add_review_post-d').validate({
        ignore: ".ignore",
        rules: {
            message_body: {
                required: true,
                minlength: 5,
            },
        },
        messages: {
            message_body: {
                required: "Review Body is Required",
                minlength: "Review body should have atleast 5 characters",
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
                        console.log(response);
                        location.reload();
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

    // delete review
    $('.reviews_container-d').on('click', '.delete_review-d', function(e) {
        let elm = $(this);
        let uuid = $(elm).attr('data-uuid');
        let container = $(elm).parents('.uuid_' + uuid);
        let postData = { review_uuid: uuid };
        modelName = 'Review';

        var removeReview = function() {
            $(container).remove();
        }
        deleteRecord(delete_course_review_url, postData, removeReview, 'removeReview', modelName);
    });

    // edit a container
    $('.reviews_container-d').on('click', '.edit_review-d', function(e) {
        let elm = $(this);
        let uuid = $(elm).attr('data-uuid');
        let container = $(elm).parents('.student_review_single_container-d');

        let rating = $(container).find('.rating_star-d').text().trim();
        let form = $('#add_review_post-d');
        $(form).find('.get_rating-d').val(rating);
        let ratingContainer = $(form).find('.rating-d');
        $(ratingContainer).find('.rating-item').each(function(i, elm) {
            if ($(elm).attr('data-rate') == rating) {
                $(elm).addClass('active');
            } else {
                $(elm).removeClass('active');
            }
        });

        $(form).find('.txt_review_body-d').val($(container).find('.review_body-d').text()).text($(container).find('.review_body-d').text());
        $(form).find('.hdn_review_uuid-d').val(uuid);

        let modal = $("#add_comment-d");
        $(modal).modal('show');
    });


    $('form').on('change', '.course_starts_at-d', function(e) {
        let elm = $(this);
        let form = $(elm).parents('form');
        let endDate = $(elm).val();
        $(form).find('.course_end_at-d').attr('min', endDate);
    });

    $('#course_slots_form-d').on('change', '#course_slot_start_date-d', function(e) {
        let elm = $(this);
        let form = $(elm).parents('form');
        let endDate = $(elm).val();
        $(form).find('#course_slot_end_date-d').attr('min', endDate);
    });
});

function getCoursePriceWithUnit(course)
{
    let text = 'Free';
    if(course.is_course_free == 0){
        let amount = course.price_usd;
        let symbol = 'USD'; // default to USD

            if(course.price_pkr > 0){
                symbol = 'PKR';
                amount = course.price_pkr;
            }
            else if(course.price_aud > 0){
                symbol = 'AUD';
                amount = course.price_aud;
            }
            else if(course.price_euro > 0){
                symbol = 'EURO';
                amount = course.price_euro;
            }

            amount = getPaddedString(amount);
            text = symbol + amount;
    }
    return text;
}

function getCoursePriceOnly(course)
{
    let text = 0;
    if(course.is_course_free == 0){
        let amount = course.price_usd;
        let symbol = 'USD'; // default to USD

            if(course.price_pkr > 0){
                symbol = 'PKR';
                amount = course.price_pkr;
            }
            else if(course.price_aud > 0){
                symbol = 'AUD';
                amount = course.price_aud;
            }
            else if(course.price_euro > 0){
                symbol = 'EURO';
                amount = course.price_euro;
            }

            amount = getPaddedString(amount);
            text = amount;
    }
    console.log(text);
    return text;
}
