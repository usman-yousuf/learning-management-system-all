function showPreLoader() {
    $('#loader').fadeIn('fast');
}

function hidePreLoader() {
    $('#loader').fadeOut('fast');
}

function uploadFilesAndGetFilesInfo(files, targetHdnInputElm = '', modelNature = 'profile', isMultiple = false) {
    if (isMultiple == true) // case: we have multiple files to be uploaded
    {
        // make an ajax hit to upload files on server
        // loop through recieveed array of paths
        // eploded the paths recieved for both image and thumbnail
        // create hidden inputs of said targetHdnInputElm name
        // concatinate bth paths and store in hidden input field
    } else { // case: we have single file
        // make an ajax hit to upload file on server
        // eploded the paths recieved for both image and thumbnail
        // concatinate bth paths and store in hidden input field

        let targetUrl = upload_files_url;
        let formdata = new FormData();
        formdata.append("medias", files);
        formdata.append("multiple", 0);
        formdata.append("nature", modelNature);
        $.ajax({
            type: "post",
            url: targetUrl,
            data: formdata,
            processData: false,
            // cache: false,
            // contentType: formdata,
            contentType: false,
            // dataType: "json",
            beforeSend: function() {
                showPreLoader();
            },
            success: function(response) {
                // console.log(response);
                if ('' != targetHdnInputElm) {
                    $(targetHdnInputElm).val(response.data[0].path).attr('value', response.data[0].path);
                }
                // let media_url = response.data.url;
                // let media_thumb = response.data.thumbnail;
                // let ratio = response.data.ratio;
                // $('#modal_donation_image-d').css({ "background-image": "url('" + media_url + "')" });
                // $('#hdn_modal_donation_image-d').val(media_url).attr('value', media_url);
                // $('#hdn_modal_donation_image_ratio-d').val(ratio).attr('value', ratio);
            },
            complete: function() {
                hidePreLoader();
            },
        });
    }
}

/**
 * Get Padded String with trailing zeros
 *
 * @param Number number
 * @param Number toDisplayDigitsCount [DEFAULT 2]
 * @param String paddingLiteral [DEFAULT '0']
 *
 * @returns $padded String
 */
function getPaddedString(string, toDisplayDigitsCount = 2, paddingLiteral = '0') {
    paddingLiteral = paddingLiteral || '0';
    string = string + '';

    let paddedString = string.length >= toDisplayDigitsCount ? string : new Array(toDisplayDigitsCount - string.length + 1).join(paddingLiteral) + string;
    return paddedString;
}

/**
 * pop an Error Alert
 *
 * @param String message
 */
function errorAlert(message) {
    Swal.fire({
        title: 'Error',
        text: message,
        icon: 'error',
        showConfirmButton: false,
        timer: 2000
    }).then((result) => {
        // location.reload();
        // $('#frm_donate-d').trigger('reset');
    });
}

/**
 * Preview an uploaded file
 *
 * @param DomElemenet input
 * @param DomElemenet targetImageElm
 * @param DomElemenet targetHiddenInput to store paths of uploaded file
 *
 * @returns void
 */
function previewUploadedFile(input, targetImgElm, targetHdnInputElm = '', modelNature = 'profile') {
    if (input.files && input.files[0]) {
        let file = input.files[0]; //
        var validExtensions = $(input).attr('data-allowed_fileExtensions').split(',');
        var fname = file.name;
        var fileExtension = fname.substring(fname.lastIndexOf('.') + 1);
        // var fileExtension = $(fname).split('.').pop();

        if (validExtensions.indexOf(fileExtension) == -1) {
            Swal.fire({
                title: 'Error',
                text: 'Only formats are allowed : ' + validExtensions.join(', '),
                icon: 'error',
                showConfirmButton: false,
                timer: 2500
            }).then((result) => {
                $(input).val('').attr('value', ''); // clear file input
                let placeholder_image = user_placeholder;
                if (modelNature) {
                    if (modelNature == 'certificate') {
                        placeholder_image = certificate_placeholder;
                    } else if (modelNature == 'experience') {
                        placeholder_image = certificate_placeholder;
                    } else if (modelNature == 'course') {
                        placeholder_image = certificate_placeholder;
                    } else if (modelNature == 'assignment') {
                        placeholder_image = assignment_placeholder;
                    }
                }
                $(targetImgElm).attr('src', placeholder_image); // default plaeholder image
            });
            return false;
        }
        // preview image
        var reader = new FileReader();
        reader.onload = function(e) {
            if ('application/pdf' == file.type) {
                $(targetImgElm).attr('src', 'https://techterms.com/img/lg/pdf_109.png');
            } else {
                $(targetImgElm).attr('src', e.target.result);
            }
        };
        reader.readAsDataURL(file);

        // upload file on server
        // console.log(targetHdnInputElm);
        if (targetHdnInputElm != '') {
            uploadFilesAndGetFilesInfo(file, targetHdnInputElm, modelNature, false);
        }
    }
}

/**
 * Preview Multiple Image files
 *
 * @param {*} input
 * @param {*} targetContainer
 */
function previewMultipleFiles(input, targetContainer) {
    if (input.files && input.files[0]) {
        files = input.files;

        // var filesCount = input.files.length;
        // for (i = 0; i < filesCount; i++) {
        //     var reader = new FileReader();

        //     reader.onload = function(event) {
        //         $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
        //     }

        //     // reader.readAsDataURL(input.files[i]);
        // }


        $.each(files, function(index, file) {
            $(targetContainer).removeClass('is-hidden');
            let clonedElm = $('#clonables-d').find('.single_image-container-d').clone();
            let targetImageElm = $(clonedElm).find('img');

            // preview in clonedElm
            var reader = new FileReader();
            // console.log(file);
            reader.onload = function(e) {
                // console.log(file);
                $(targetImageElm).attr('src', e.target.result);
            }
            reader.readAsDataURL(file);

            $(targetContainer).append(clonedElm);
            // console.log(targetContainer, clonedElm);
        })
    }
}

/**
 * Switch between two modals
 *
 * @param {*} source
 * @param {*} target
 * @param {*} is_reset
 */
function switchModal(source, target, is_reset = false) {
    $('#' + source).modal('hide');
    if (is_reset) {
        let reset_form = $('#' + target).find('form');
        $(reset_form).each(function(index, form) {
            $(form)[0].reset();
        });
    }
    setTimeout(function() {
        $('#' + target).modal('show');
    }, 400);
}

/**
 * Prompt and then delete record on confirm
 *
 * @param {String} targetUrl
 * @param {Object} postData
 * @param {DomeElmenet*} caller
 * @param {Function to callback} callbackFunc
 * @param {String} modelName
 */
function deleteRecord(targetUrl, postData, caller, callbackFunc, modelName = 'record') {
    Swal.fire({
        title: 'Warning',
        text: 'Are you sure you want to delete this ' + modelName,
        icon: 'warning',
        showConfirmButton: true,
        showCancelButton: true,
        // timer: 2000,
        cancelButtonText: 'Yes Please',
        cancelButtonText: 'Cancel',
        // closeOnConfirm: false,
        // closeOnCancel: false

    }).then((result) => {
        if (result['isConfirmed']) {
            $.ajax({
                url: targetUrl,
                type: 'POST',
                dataType: 'json',
                data: postData,
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
                            caller.call(callbackFunc);
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000
                        }).then((result) => {});
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
            // return false;

        } else {
            // do nothing
            // Swal.fire({
            //     title: 'Error',
            //     text: 'Something went wrong while deleting ' + modelName,
            //     icon: 'error',
            //     showConfirmButton: false,
            //     timer: 2000
            // }).then((result) => {
            //     console.log(result);
            // });
        }
    });
}

/**
 * Get Date in database default formate
 *
 * @param date fullDate
 * @returns
 */
function getFormattedDate(fullDate = null) {
    // const d = new Date('Thu Apr 01 2021 00:00:00 GMT+0500 (Pakistan Standard Time)');
    const d = (null == fullDate) ? new Date() : new Date(fullDate);
    month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    formattedDate = year + '-' + month + '-' + day
    return formattedDate;
}

function getRelativeMonthFormattedDate(cDate, monthStepCount, mode) {
    let d = new Date(cDate);
    // console.log(d);
    // temp = d.addDays(monthStepCount);
    // console.log(temp);
    // date
    let nextDate = '' + d.getDate();
    if (nextDate.length < 2) {
        nextDate = '0' + nextDate;
    }

    let year = d.getFullYear();

    let nextMonth = d.getMonth() + 1 + monthStepCount;
    if (mode == 'add') {
        if (nextMonth > 12) {
            nextMonth = nextMonth - 12;
            year = year + 1;
        }
    } else {
        // console.log(nextMonth)
        if (nextMonth < 1) {
            nextMonth = 12 - (nextMonth * -1);
            // nextMonth = 12;
            year = year - 1;
        }
    }
    nextMonth = '' + nextMonth;

    if (nextMonth.length < 2) {
        nextMonth = '0' + nextMonth;
    }

    newDate = year + '-' + nextMonth + '-' + nextDate;
    return newDate;
}

/**
 * update given string with new value added, removed
 *
 * @param String given_string
 * @param String targetString
 *
 * @returns String updated_string
 */
function addUpdateCommaSeperatedString(given_string, targetString) {
    if (given_string.indexOf(targetString) < 0) { // case, day num does not exist in selection
        if ('' != given_string) {
            given_string += ',' + targetString;
        } else {
            given_string += targetString;
        }
    } else {
        if (given_string.indexOf(',' + targetString) > -1) {
            given_string = given_string.replace(',' + targetString, '');
        } else {
            if (given_string.indexOf(targetString + ',') > -1) {
                given_string = given_string.replace(targetString + ',', '');
            } else {
                given_string = given_string.replace(targetString, '');
            }
        }
    }
    return given_string;
}

$(function(event) {

    if ($(".tagged_select2").length > 0) {
        $(".tagged_select2").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })
    }

    // >= method in jquery validator
    jQuery.validator.addMethod("greaterThan",
        function(value, element, params) {
            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date($(params).val());
            }
            return isNaN(value) && isNaN($(params).val()) ||
                (Number(value) > Number($(params).val()));
        }, 'Must be greater than {0}.');

    // Add >= method in jquery validator
    jQuery.validator.addMethod("greaterThanOrEqual",
        function(value, element, params) {
            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) >= new Date($(params).val());
            }
            return isNaN(value) && isNaN($(params).val()) ||
                (Number(value) >= Number($(params).val()));
        }, 'Must be greater or equal to {0}.');
});
