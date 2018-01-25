$(document).ready(function(){
 
    msgs_count = 0;
    dialog_id = $('.ad__messages').data('dialog');
    read_messages = false;
    
    function checkMessages()
    {
        var current_msgs_count = $('.dialogs_link span.cabLink__info').html();
        var dialog_link = $('.cabinet__link.dialogs_link');
        var lots = $('.lot_my.lot_active.ad_my');
        
        if(typeof dialog_id == 'undefined') {
            var url = "/dialogs/check-messages";
        } else {
            var url = "/dialogs/check-messages/" + dialog_id;
        }
        
        $.ajax({
            method: "GET",
            url: url,
            cache: false,
            dataType: 'json',
            success: function(response) {
                console.log('check!');
                
                $('.b-num_msg').html(response.count);
                msgs_count = response.count;

                if(response.count != current_msgs_count){
                    $('.dialogs_link span.cabLink__info').html(response.count);
                    $('.dialogs_link, .dialogs_link span').addClass('blue_background');
   
                    // отображение диалогов в хедере
                    updateHeaderDialogs(response.dialogs);
                    
                    if($('.ad__messages').length && response.messages.length)
                    {
                        // выводим сообщения в диалоге
                        showDialogMessages(response.messages);
                        read_messages = true;
                        setReadMessages(dialog_id);
                    } else {

                    }
                }
            }            
        });
    }
           
    function setReadMessages(dialog_id)
    {
        $('.dialogs_link, .dialogs_link span').removeClass('blue_background');
        
        if(dialog_id == 'undefined' || msgs_count < 1 || read_messages === false)
        {
            return false;
        }
        read_messages = false;
        $.ajax({
            method: "POST",
            url: "/dialogs/read-messages/" + dialog_id,
            cache: false,
            dataType: 'json',
            success: function(response) {
//                console.log('____');
                console.log(response);
            }            
        });
        
        return false;
    }
    
    // при движении мышкой или по нажатию любой кнопки - все сообщения 
    // текущего диалога отмечаем как прочитанные
    $(document).mousemove(function(){
        setReadMessages(dialog_id);
    });
    
    $(document).keypress(function(){
        setReadMessages(dialog_id);
    });
    
    
    // проверка на наличие новых сообщений    
//    checkMessages();
    setInterval(checkMessages, 15000);


    function updateHeaderDialogs(dialogs){
        // отображение диалогов в хедере
        var dialogs_count = dialogs.length;
        var header_view = '';
        for(var i = 0; i < dialogs_count; i++)
        {
            header_view += '<li class="b-list__item_msg clearfix">' +
                '<a href="' + dialogs[i].url +'" class="b-msgLink__lot">' +
                    '<h5 class="adNew__name">' + dialogs[i].title + '</h5>' +
                    '<span> от ' + dialogs[i].from + '</span>' +
                '</a>' +
                '<a href="' + dialogs[i].url +'" class="b-msgLink__messages adNew__name">' +
                    '<span class="b-msgLink_icon b-message__icon"></span>' +
                    '<span class="b-msgLink_text">' +
                        '<span class="b-num_msg">' + dialogs[i].count + '</span>' +
                        ' сообщений' +
                    '</span>' +
                '</a>' +
            '</li>';
        }

        $('.b-list_msg.js-messages_new').html(header_view);
        return true;
    }
    
    function showDialogMessages(messages){
        var messages_count = messages.length;
        var dialog_view = '';
        for(var j = 0; j < messages_count; j++)
        {
            dialog_view += '<div class="wrap_message">' +
                        '<div class="ad__message message_respond">' +
                            '<aside>' +
                                '<img src="/img/user.svg" class="message__avatar">' +
                            '</aside>' +
                            '<div class="message__content">' +
                                '<span class="message__date">' + messages[j].time + '</span>' +
                                '<p class="message__text">' + messages[j].text + '</p>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
        }
        $('.ad__messages').append(dialog_view);
        $(".ad__messages").animate({ scrollTop: $(".ad__messages")[0].scrollHeight}, 1000);
        return true;
    }
});
