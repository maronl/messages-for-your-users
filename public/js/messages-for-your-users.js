jQuery( document ).ready(function() {
    if(jQuery('#messages-to-your-users')){

        jQuery('#messages-to-your-users').modal('show');

        ajaxurl = m4yu.url;

        console.log('set as read');

        jQuery.post(
            ajaxurl,
            {'action': 'set_message_as_read', 'user_id':  jQuery('#messages-to-your-users').data('user-id'), 'message_id': jQuery('#messages-to-your-users').data('message-id')},
            function(response){
                console.log(response);
            },
            'json'
        );
    }


});
