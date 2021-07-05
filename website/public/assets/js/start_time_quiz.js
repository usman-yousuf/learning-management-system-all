// $(document).ready(function() {

// COUNT DOWN TIMER
var mytime = document.getElementById("duration-d").value;
var currentTime = document.getElementById("date_now-d").value;
var distance;
var countDownDate = new Date(mytime).getTime();
var now = new Date(currentTime).getTime();

// Update the count down every 1 second
var x = setInterval(function() {
    // Get today's date and time
    now += 1000;
    // Find the distance between now and the count down date
    distance = countDownDate - now;


    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("hrs").innerHTML = getPaddedString(hours);
    document.getElementById("mins").innerHTML = getPaddedString(minutes);
    document.getElementById("seconds").innerHTML = getPaddedString(seconds);

    // check if the student submitted the quiz


    //   If the count down is over, write some text
    if (distance < 1) {
        clearInterval(x);
        $('.is_time_out-d').val('1');
        $('#frm_student_mcq-d').trigger('submit');
        // document.getElementById("demo").innerHTML = "EXPIRED";
    }
}, 1000);


let is_warned = false;
$("#frm_student_mcq-d").validate({
    ignore: ".ignore",
    rules: {
        quiz_uuid: {
            required: true,
        },
        course_uuid: {
            required: true,
        },
    },
    messages: {
        quiz_uuid: {
            required: "Quiz is required",
        },
        course_uuid: {
            required: "Course is required",
            // minlength: "Option should have atleast 1 character",
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
                        window.location.href = STUDENT_DASHBOARD_URL;
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
    }
});

// trigger submit on click
$('#frm_student_mcq-d').on('click', '.submit_student_mcqs_form-d', function(e) {
    let elm = $(this);
    var submitQuiz = function() {
        $(elm).parents('form').trigger('submit');
    }
    message = 'Are you sure to Submit this Quiz';
    promptBox(submitQuiz, 'submitQuiz', message);

    // if (is_warned == false) {
    //     var selectedOption = $(".ans_option-d:checked");
    //     if (selectedOption.length < 1) {
    //         // if ($('.is_time_out-d').val() != '1') {
    //         errorAlert('Please select a choice');
    //         is_warned = true;
    //         return false;
    //         // }
    //     }
    // } else {
    //     $(this).parents('form').trigger('submit');
    // }
});



$("#frm_student_test-d").validate({
    ignore: ".ignore",
    rules: {
        quiz_uuid: {
            required: true,
        },
        course_uuid: {
            required: true,
        },
    },
    messages: {
        quiz_uuid: {
            required: "Quiz is required",
        },
        course_uuid: {
            required: "Course is required",
            // minlength: "Option should have atleast 1 character",
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
                        window.location.href = STUDENT_DASHBOARD_URL;
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
    }
});

// trigger submit on click
$('#frm_student_test-d').on('click', '.submit_student_test_form-d', function(e) {
    let elm = $(this);
    var submitQuiz = function() {
        $(elm).parents('form').trigger('submit');
    }
    message = 'Are you Sure to Submit this Quiz';
    promptBox(submitQuiz, 'submitQuiz', message);
    // if (is_warned == false) {
    //     if (!$('.ans_body-d').filter(function() { return $.trim(this.value).length > 0; }).length) {
    //         errorAlert('Please attempt atleast 1 question ');
    //         is_warned = true;
    //         return false;
    //     }
    // } else {
    //     $(this).parents('form').trigger('submit');
    // }

});
// });