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
        postData = { };
        deleteRecord(targetUrl, postData, removeNotification, 'removeNotification', modelName);
    });
});