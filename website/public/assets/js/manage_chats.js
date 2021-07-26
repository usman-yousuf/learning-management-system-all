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
    let existing_users_keywords = '';
    $('#chat_sidebar-d').on('submit', '#frm_search_existing_chat_users-d', function(e) {
        let keywords = $(this).find('.existing_chat_search_input-d').val().trim();
        existing_users_keywords = (keywords.length < 1) ? null : keywords;
        $.ajax({
            url: get_chatted_users_url,
            type: 'POST',
            dataType: 'json',
            data: { keywords: existing_users_keywords },
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                if (response.status) {
                    let data = response.data;
                    if (data.total_chats > 0) {
                        if ($('.cloneable_containers-d').find('#cloneable_existing_chat_single_container-d').length > 0) {
                            $('.existing_chat_users_listing_container-d').html('');
                            $.each(data.chats, function(i, item) {
                                let profile = item.other_members[0].profile;
                                let clonedElm = $('.cloneable_containers-d').find('#cloneable_existing_chat_single_container-d').clone();
                                $(clonedElm).removeAttr('id').addClass('uuid_' + item.uuid).attr('data-uuid', item.uuid);
                                $(clonedElm).find('.chat_last-d').text(getTruncatedString(item.last_message.message));
                                let profile_name = getTruncatedString(profile.first_name + ' ' + profile.last_name)
                                $(clonedElm).find('.chat_member_profile_name-d').text(profile_name);
                                $(clonedElm).find('.chat_member_profile_image-d').attr('src', profile.profile_image);
                                $(clonedElm).find('.message_time-d').text(item.last_message.create_time);

                                $('.existing_chat_users_listing_container-d').append(clonedElm);
                            });
                        }
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No Records Found',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            // location.reload();
                            // $('#frm_donate-d').trigger('reset');
                        });
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
    });

    // load new users i have not chatted with yet
    let new_users_keywords = '';
    $('#modal_new_message-d').on('submit', '#frm_search_new_chat_users-d', function(e) {
        let keywords = $(this).find('.new_chat_search_input-d').val().trim();
        new_users_keywords = (keywords.length < 1) ? null : keywords;
        $.ajax({
            url: get_not_chatted_users_url,
            type: 'POST',
            dataType: 'json',
            data: { keywords: new_users_keywords },
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                if (response.status) {
                    let data = response.data;
                    if (data.total_profiles > 0) {
                        if ($('.cloneable_containers-d').find('#cloneable_new_chat_user_single_container-d').length > 0) {
                            $('.new_chat_users_listing_container-d').html('');
                            $.each(data.profiles, function(i, profile) {
                                let clonedElm = $('.cloneable_containers-d').find('#cloneable_new_chat_user_single_container-d').clone();
                                $(clonedElm).removeAttr('id').addClass('uuid_' + profile.uuid).attr('data-uuid', profile.uuid);
                                let profile_name = getTruncatedString(profile.first_name + ' ' + profile.last_name)
                                $(clonedElm).find('.profile_name-d').text(profile_name);
                                $(clonedElm).find('.profile_image-d').attr('src', profile.profile_image);
                                $(clonedElm).find('.profile_type-d').text(profile.profile_type);

                                $('.new_chat_users_listing_container-d').append(clonedElm);
                            });
                        }
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No Records Found',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {
                            // location.reload();
                            // $('#frm_donate-d').trigger('reset');
                        });
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
    });

    // send message to a new user
    $('.new_chat_users_listing_container-d').on('click', '.send_new_message-d', function(e) {
        let elm = $(this);
        console.log(elm);
        $('#modal_new_message-d').modal('hide'); // hide modal

        let mainContainer = $(elm).parents('.new_chat_user_single_container-d');
        let profile_uuid = $(mainContainer).attr('data-uuid');
        let profile_image = $(mainContainer).find('.profile_image-d').attr('src');
        let profile_name = $(mainContainer).find('.profile_name-d').text();


        $('.existing_chat_single_container-d').removeClass('active'); // make every other chat inactive

        // set chat header
        $('.chat_header-d').addClass('new_chat-d').attr('profile_uuid', profile_uuid);
        $('.chat_header-d').find('.chat_title-d').text(profile_name); // set profile name in chat heading
        $('.chat_header-d').find('.chat_image-d').attr('src', profile_image); // set profile image
        $('.chat_messages_content_container-d').html(''); //  empty chat container

        // setuup form properties
        let form = $('#frm_send_message-d');
        $(form).find('#hdn_chat_uuid-d').val('');
        $(form).find('#hdn_reciever_uuid-d').val(profile_uuid);

        // determine if this user is already added in sidebar for chatting
        let targetSelector = '.profile_uuid_' + profile_uuid;
        if ($('.existing_chat_users_listing_container-d').find(targetSelector).length < 1) {
            // add new user in chatted users list
            if ($('.cloneable_containers-d').find('#cloneable_existing_chat_single_container-d').length > 0) {
                let clonedElm = $('.cloneable_containers-d').find('#cloneable_existing_chat_single_container-d').clone();
                $(clonedElm).removeAttr('id').addClass('active').addClass('new_chat-d').removeAttr('data-uuid').addClass('profile_uuid_' + profile_uuid).attr('data-profile_uuid', profile_uuid);

                $(clonedElm).find('.chat_member_profile_name-d').text(profile_name);
                $(clonedElm).find('.chat_member_profile_image-d').attr('src', profile_image);
                $(clonedElm).find('.chat_member_profile_name-d').text(profile_name);
                $(clonedElm).find('.chat_member_profile_image-d').attr('src', profile_image);
                // $(clonedElm).find('.message_time-d').text(item.last_message.create_time);

                $('.existing_chat_users_listing_container-d').append(clonedElm);
            }
        }
    });


    // load messages of an indivisual chat
    $('.existing_chat_users_listing_container-d').on('click', '.existing_chat_single_container-d', function(e) {
        let elm = $(this);
        if ($(elm).hasClass('new_chat-d') == false) {
            let chat_uuid = $(elm).attr('data-uuid');
            existing_users_keywords = '';
            if ($(elm).hasClass('active') == false) {
                $('.existing_chat_single_container-d').removeClass('active');
                $(elm).addClass('active');
                current_chat_uuid = chat_uuid;
                current_page_no = 1;
                data = { chat_uuid: chat_uuid, offset: (current_page_no - 1) * per_page, limit: per_page, keywords: existing_users_keywords };

                updateChatMessagesContainer(data, current_page_no, is_new = true);

                let form = $('#frm_send_message-d');
                $(form).find('#hdn_chat_uuid-d').val(current_chat_uuid);
                $(form).find('#hdn_reciever_uuid-d').val('');
            } else {
                let mainContainer = elm;
                $(mainContainer).addClass('active');

                let profile_uuid = $(mainContainer).attr('data-profile_uuid');
                let profile_image = $(mainContainer).find('.chat_member_profile_image-d').attr('src');
                let profile_name = $(mainContainer).find('.chat_member_profile_name-d').text();


                $('.existing_chat_single_container-d').removeClass('active'); // make every other chat inactive

                // set chat header
                $('.chat_header-d').addClass('new_chat-d').attr('profile_uuid', profile_uuid);
                $('.chat_header-d').find('.chat_title-d').text(profile_name); // set profile name in chat heading
                $('.chat_header-d').find('.chat_image-d').attr('src', profile_image); // set profile image
                $('.chat_messages_content_container-d').html(''); //  empty chat container

                // setuup form properties
                let form = $('#frm_send_message-d');
                $(form).find('#hdn_chat_uuid-d').val('');
                $(form).find('#hdn_reciever_uuid-d').val(profile_uuid);
            }
        }
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

    //delete chat
    $(".existing_chat_users_listing_container-d").on('click', '.delete_chat-d', function(e) {
        let elm = $(this);
        let container = $(elm).parents('.existing_chat_single_container-d');
        var removeChat = function() {
            // console.log($(container).siblings('.existing_chat_single_container-d'));
            let siblings = $(container).siblings('.existing_chat_single_container-d')
            $(siblings[0]).trigger('click');
            $(container).remove();
        }
        modelName = 'Chat';

        if ($(container).hasClass('new_chat-d')) {
            promptBox(removeChat, removeChat, 'Are you sure to delete this Chat?');
            return false;
        }

        let mainContainer = $(elm).find(".existing_chat_users_listing_container-d");
        let uuid = $(container).attr('data-uuid').trim();

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

    $('#frm_send_message-d').validate({
        ignore: ".ignore",
        rules: {
            // chat_uuid: {
            //     required: true
            // },
            chat_message: {
                required: true
            },
            reciever_uuid: {
                required: {
                    depends: function(element) {
                        return ($("#hdn_chat_uuid-d").val() == '');
                    }
                }
            },
        },
        messages: {
            chat_uuid: {
                required: "Chat UUID is Required"
            },
            chat_message: {
                required: "Message is Required"
            },
            reciever_uuid: {
                required: "Reciever UUID is Required"
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
            var form_data = $(form).serialize();
            // console.log(form_data);

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: form_data,
                beforeSend: function() {
                    showPreLoader();
                },
                success: function(response) {
                    console.log(response);
                    if (response.status) {
                        let data = response.data;
                        // set uuid if new message in sidebar
                        // set last message in sidebar
                        // apply sockets
                        // append message in messages listing container

                        // if (data.total_chats > 0) {
                        // if ($('.cloneable_containers-d').find('#cloneable_existing_chat_single_container-d').length > 0) {
                        //     $('.existing_chat_users_listing_container-d').html('');
                        // $.each(data.chats, function(i, item) {
                        //     let profile = item.other_members[0].profile;
                        //     let clonedElm = $('.cloneable_containers-d').find('#cloneable_existing_chat_single_container-d').clone();
                        //     $(clonedElm).removeAttr('id').addClass('uuid_' + item.uuid).attr('data-uuid', item.uuid);
                        //     $(clonedElm).find('.chat_last-d').text(getTruncatedString(item.last_message.message));
                        //     let profile_name = getTruncatedString(profile.first_name + ' ' + profile.last_name)
                        //     $(clonedElm).find('.chat_member_profile_name-d').text(profile_name);
                        //     $(clonedElm).find('.chat_member_profile_image-d').attr('src', profile.profile_image);
                        //     $(clonedElm).find('.message_time-d').text(item.last_message.create_time);

                        //     $('.existing_chat_users_listing_container-d').append(clonedElm);
                        // });
                        // }
                        // } else {
                        // Swal.fire({
                        //     title: 'Error',
                        //     text: 'Chal phut yahan se',
                        //     icon: 'error',
                        //     showConfirmButton: false,
                        //     timer: 2000
                        // }).then((result) => {
                        //     // location.reload();
                        //     // $('#frm_donate-d').trigger('reset');
                        // });
                        // }

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
        },
    });

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
