// -----form validation------
// (function() {
//     "use strict";
//     window.addEventListener(
//         "load",
//         function() {
//             // Get the forms we want to add validation styles to
//             var forms = document.getElementsByClassName("needs-validation");
//             // Loop over them and prevent submission
//             var validation = Array.prototype.filter.call(forms, function(form) {
//                 form.addEventListener(
//                     "submit",
//                     function(event) {
//                         if (form.checkValidity() === false) {
//                             event.preventDefault();
//                             event.stopPropagation();
//                         }
//                         form.classList.add("was-validated");
//                     },
//                     false
//                 );
//             });
//         },
//         false
//     );
// })();
// -----form validation end------




//  Side bar Menu Toggle Script
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});
//  Side bar Menu Toggle Script End


$(function(event) {
    // Contact Us Page Form - START
    $('#frm_contact_us-d').validate({
        ignore: ".ignore",
        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            email: {
                required: true,
                email: true,
            },
            subject: {
                required: true,
                minlength: 3,
            },
            message: {
                required: true,
                minlength: 5,
            },
        },
        messages: {
            name: {
                required: "Name is Required",
                minlength: "Name Must be atleast 3 Characters long"
            },
            email: {
                required: "Email is Required",
                email: "Email Format is invalid"
            },
            subject: {
                required: "Subject is Required",
                minlength: "Subject Must be atleast 3 Characters long"
            },
            message: {
                required: "Message is Required",
                minlength: "Message Must be atleast 5 Characters long"
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
                            let model = response.data;
                            $(form).trigger('reset');
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
    // Contact Us Page Form - END
});