$(function(event) {
    // delete notification
    $('.notification_listing_container-d').on('click', '.delete_notification-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.single_notification-d');
        var removeNotification = function() {
            $(container).remove();
        }
        modelName = 'Notification';
        targetUrl = $(elm).attr('data-href');
        postData = {};
        deleteRecord(targetUrl, postData, removeNotification, 'removeNotification', modelName);
    });

    let current_chat_uuid = '';
    let current_page_no = 1;
    let per_page = 6;
    let total_records = 0;

    let chatContainer = $('.chat_messages_content_container-d');
    $(chatContainer).scrollTop($(chatContainer)[0].scrollHeight);

    // load chat messages on sidebar chat click
    $('.existing_chat_users_listing_container-d').on('click', '.existing_chat_single_container-d', function(e) {
        let elm = $(this);
        let chat_uuid = $(elm).attr('data-uuid');
        if ($(elm).hasClass('active')) {
            return false;
        }
        $('.existing_chat_single_container-d').removeClass('active');
        $(elm).addClass('active');
        current_chat_uuid = chat_uuid;
        current_page_no = 1;
        data = { chat_uuid: chat_uuid, offset: (current_page_no - 1) * per_page, limit: per_page };

        updateChatMessagesContainer(data, current_page_no, is_new = true);
    });

    // load more messages on scroll up and prepend them in box
    $('.chat_messages_content_container-d').scroll(function() {
        let chatMessageContainer = $(this);
        let containerScrollHeight = $(chatMessageContainer).get(0).scrollHeight;
        let containerHeight = $(chatMessageContainer).height();
        let ScrollTopPosition = Math.ceil($(chatMessageContainer).scrollTop());
        let currentScrollPosition = containerHeight + ScrollTopPosition;

        if (ScrollTopPosition < 100) {
            // console.log($('.chat_messages_content_container-d').find('.single_message_container-d').length, total_records)
            if (total_records > $('.chat_messages_content_container-d').find('.single_message_container-d').length) {
                let chat_uuid = $('.chat_header-d').attr('data-chat_uuid');
                current_page_no++;
                data = { chat_uuid: chat_uuid, offset: (current_page_no - 1) * per_page, limit: per_page };
                updateChatMessagesContainer(data, current_page_no, is_new = false);
                $(chatMessageContainer).scrollTop(150);
            }
        }
    });

    //delete test question
    $(".existing_chat_users_listing_container-d").on('click', '.delete_chat-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.existing_chat_single_container-d');
        let mainContainer = $(elm).find(".existing_chat_users_listing_container-d");
        let uuid = $(container).attr('data-uuid').trim();

        var removeChat = function() {
            $(container).remove();
            $(container).siblings('.existing_chat_single_container-d')[0].trigger('click');
        }
        modelName = 'Chat';
        targetUrl = delete_chat_url;
        // console.log(modelName, targetUrl)
        // $(container).remove();

        postData = { chat_uuid: uuid };
        deleteRecord(targetUrl, postData, removeChat, 'removeChat', modelName);

    });


    /**
     * Fetches messages list from server and prepends in chat messages container
     *
     * @param Array[] data
     * @param Integer current_page_no
     * @param Boolean is_new
     */
    function updateChatMessagesContainer(data, current_page_no, is_new = false) {
        $.ajax({
            url: get_chat_messages_url,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                if (response.status) {
                    let data = response.data;
                    console.log(data.chat);
                    total_records = data.chat.total_messages_count;
                    current_page_no++; // udpate current page No for next retrieval
                    if (is_new) {
                        $('.chat_messages_content_container-d').html('').html(data.html); // update chat messages listing container
                        // update chat message container heading
                        $('.chat_header-d').attr('data-chat_uuid', data.chat.uuid);
                        let member = data.chat.other_members[0].profile;
                        $('.chat_header-d').find('.chat_image-d').attr('src', member.profile_image);
                        $('.chat_header-d').find('.chat_title-d').text(member.first_name + ' ' + member.last_name);
                    } else {
                        $('.chat_messages_content_container-d').prepend(data.html);
                    }
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
    }

    /*
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
            price_usd: {
                required: {
                    depends: function(element) {
                        return $("#rb_is_course_paid-d").is(':checked')
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
                        return $("#rb_is_course_paid-d").is(':checked')
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
            price_pkr: {
                required: 'Price (PKR) is Required'
            },
            price_usd: {
                required: 'Price (USD) is Required'
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
    */
});