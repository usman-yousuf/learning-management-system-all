$(document).ready(function() {


    // approve teacher  - START
    $('.frm_approve_teacher-d').each(function(){
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
                                targetId = '#teacher-d'+uuid;
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
    $('.frm_approve_teacher_course-d').each(function(){
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

                            targetId = '#teacher_course-d'+uuid;
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
});