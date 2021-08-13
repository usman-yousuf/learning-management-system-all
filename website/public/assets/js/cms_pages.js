$(function(event) {

    pageUrl = new URL(window.location.href);
    let last_page = pageUrl.searchParams.get('last_page');
    if (null != last_page) {
        if ('profile' == last_page) {
            $('#sidebar-wrapper').find('.list-group-item-action').hide();
            $('#sidebar-wrapper').find('.cms_pages-d').show();
            $('#sidebar-wrapper').find('.logout_link-d').show();

            // $('.logo_link-d').attr('href', 'javascript:void(0)');
            $('.notification_link-d').attr('href', 'javascript:void(0)');
            $('.top_nav_bar_profile_setting_link-d').hide();
            $('.top_nav_bar_profile_divider-d').hide();
        }
    }
    // const params = new URLSearchParams(window.location.search)
    // console.log(params);
    // for (const param of params) {
    //     console.log(param)

    // }

});