// $(document).ready(function() {

    // COUNT DOWN TIMER 
    var mytime =  document.getElementById("duration-d").value;
    var currentTime =  document.getElementById("date_now-d").value;
    var distance;
    var countDownDate = new Date(mytime).getTime();
    var now = new Date(currentTime).getTime();
    
    // Update the count down every 1 second
    var x = setInterval(function() {
    // Get today's date and time
        now +=1000;
    // Find the distance between now and the count down date
        distance = countDownDate - now;
    

    // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("hrs").innerHTML = hours;
        document.getElementById("mins").innerHTML = minutes;
        document.getElementById("seconds").innerHTML =  seconds;
    
    // check if the student submitted the quiz    
      

    //   If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);

    $("#frm_test_mcqs-d").validate({
        ignore: ".ignore",
        rules: {
            // question_body: {    
            //     required: true,
            //     minlength: 5
            // },
            opt: {
                required: true,
                minlength: 1
            },
            // boolean_option_2: {
            //     required: true,
            //     minlength: 1,
            // },
        },
        messages: {
            // question_body: {
            //     required: "Question is required",
            //     minlength: "Question should have atleast 5 character",
            // },
            opt: {
                required: "Option 1 is required",
                // minlength: "Option should have atleast 1 character",
            },
            // boolean_option_2: {
            //     required: "Option 2  is required",
            //     minlength: "Option should have atleast 1 character",
            // },

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
            var selectedOption = $(".ans_option-d:checked");
            if (selectedOption.length < 1) {
                errorAlert('Please select a choice');
                return false;
            }

        let answers = [];
        let questions = [];
        $('.question_container-d').find('.single_question_container-d').each(function(i, container) {
            let qcx = $(container).find('.ans_option-d');

            
                // determine option checked
                let is_correct = false;
                if ($(qcx).is(':checked')) {
                    is_correct = true;
                }

                // determine ans_uuid
                let ans_uuid = null;
                if ($(qcx).attr('value') && ('' != $(qcx).attr('value'))) { // case of update
                    ans_uuid = $(qcx).attr('value');
                }

                // determine ans_body
                let ans_body = $(container).find('.txt_option_body-d').val();
                answers.push({
                    is_correct: is_correct,
                    body: ans_body,
                    answer_uuid: ans_uuid
                });

        });
        $(form).find('#answers_json-d').val(JSON.stringify(answers));

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
                                alert('quiz submitted');
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



// });