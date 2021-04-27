$(function(event) {
    // Mobile Phone Number
    var mobilePhoneInputField = document.querySelector("#mobile_phone-d");
    var mobilePhoneInput = window.intlTelInput(mobilePhoneInputField, {
        nationalMode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });
    mobilePhoneDialCode = mobilePhoneInput.getSelectedCountryData().dialCode;
    $('#mobile_country_code-d').val(mobilePhoneDialCode).attr('value', mobilePhoneDialCode);

    // on change of input field
    $(mobilePhoneInputField).on('change', function(e) {
        // console.log(mobilePhoneInput.getSelectedCountryData());
        mobilePhoneDialCode = mobilePhoneInput.getSelectedCountryData().dialCode;
        $('#mobile_country_code-d').val(mobilePhoneDialCode).attr('value', mobilePhoneDialCode);
    });

    // Other Phone Number
    var phoneInputField = document.querySelector("#phone_phone-d");
    var phoneInput = window.intlTelInput(phoneInputField, {
        nationalMode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });
    phoneDialCode = phoneInput.getSelectedCountryData().dialCode;
    $('#phone_country_code-d').val(phoneDialCode).attr('value', phoneDialCode);

    // on change of input field
    $(phoneInputField).on('change', function(e) {
        // console.log(phoneInput.getSelectedCountryData());
        phoneDialCode = phoneInput.getSelectedCountryData().dialCode;
        $('#phone_country_code-d').val(phoneDialCode).attr('value', phoneDialCode);
    });

});