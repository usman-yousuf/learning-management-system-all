function showPreLoader() {
    $('#loader').fadeIn('fast');
}

function hidePreLoader() {
    $('#loader').fadeOut('fast');
}

/**
 * Preview a file before uploading
 *
 * @param DomElemenet input
 * @param DomElemenet targetImageElm
 *
 * @returns void
 */
function previewImage(input, targetImageElm) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(targetImageElm).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
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
    $('#' + source).removeClass('is-active');
    if (is_reset) {
        let reset_form = $('#' + target).find('form');
        $(reset_form).each(function(index, form) {
            $(form)[0].reset();
        });
    }
    setTimeout(function() {
        $('#' + target).addClass('is-active');
    }, 400);

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

$(function(event) {});