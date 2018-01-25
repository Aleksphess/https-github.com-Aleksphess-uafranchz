$(document).ready(function() {
// create/delete bookmarks
    $(document).on('click', '.js-franchise-favorite-toggle-status', function(e){
        e.preventDefault();
        var link = $(this);
        var lot_Id = link.data('lot');

        $.ajax({
            type: 'post',
            url : '/user-bookmarks/bookmark/' + lot_Id,
            cache: false,
            success: function(response){
                if(response.status == true)
                {
                    link.toggleClass('is--active');
                }
            }
        });
        return false;
    });
    // create answer form
    $("form#answers").on('submit',function(e){

        e.preventDefault();

        var answer=$('#ans').val();
        var answer_id=$('#answer_id').val();

        $.ajax({
            type: 'POST',
            url: '/dialogs/send-answer',
            data: {

                answer: answer,
                answer_id:answer_id,
            },
            success: function (data) {
                $('#answers').trigger("reset");
                $('.js-answer-status').text('Сообщение отправлено, успешно!');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    if ($('.js-write-answer').length) {
        $('.js-write-answer').on('click', (e) => {
            let answerId = $(e.currentTarget).data('id');

        $('input[name="answer_id"]').val(answerId);
    });
    }
    // create send-message
    $("form#message").on('submit',function(e){

        e.preventDefault();
        var name=$('#name').val();
        var quest=$('#quest').val();
        var dialog_id=$('#dialog_id').val();
        $.ajax({
            type: 'POST',
            url: '/dialogs/send-message',
            data: {
                name: name,
                quest: quest,
                dialog_id:dialog_id,
            },
            success: function (data) {
                $('#message').trigger("reset");
                $('.js-answer-status').text('Сообщение отправлено, успешно!');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    if ($('.js-write-question').length) {
        $('.js-write-question').on('click', (e) => {
            let dialogId = $(e.currentTarget).data('id');

        $('input[name="dialog_id"]').val(dialogId);
    });
    }
    // delete message
    $(document).on('click', '.js-delete-message', function(e){
        e.preventDefault();
        var link = $(this);
        var dialogId = link.data('id');
       // alert(dialogId);
        $.ajax({
            type: 'post',
            url : '/dialogs/delete-message',
            data: {dialogId:dialogId},
            cache: false,
            success: function (data) {

                $('.js-delete-'+dialogId).css('display', 'none');
            },
        });
        return false;
    });
    // delete img
   /* $(document).on('click', '.js-remove-media', function(e){
        e.preventDefault();
        var link = $(this);
        var img = link.data('img');
        var index = link.data('index');
        // alert(dialogId);
        $.ajax({
            type: 'post',
            url : '/lots/delete-img',
            data: {img:img,index:index},
            cache: false,
            success: function (data) {
               

                $('.js-img-'+index).css('display', 'none');
            },
        });
        return false;
    });*/
    // work with login form
    $("form#login").on('submit',function(e){

        e.preventDefault();
        var email=$('#email').val();
        var password=$('#password').val();

        $.ajax({
            type: 'POST',
            url: '/auth/sign-in',
            data: {
                email: email,
                password: password,

            },
            success: function (data) {
                if(data=='success')
                {
                    location.href='http://test8.digitalforce.ua/user/index'
                }
                else
                {
                    $('.js-answer-status').text(data);
                }

            },
            error: function (error) {

                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // work with registration form
    $("form#sign-up").on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/auth/sign-up',
            data: $( this ).serialize(),
            success: function (data) {
                if(data=='success')
                {
                    $('#sign-up').trigger("reset");
                    $('.js-answer-status').text('Вы успешно зарегестрированы, вам на почту выслано подтверждение регистрации');
                }
                else
                {
                    $('.js-answer-status').text(data);
                }

            },
            error: function (error) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // user basic change-settings
    $("form#change-settings").on('submit',function(e){
        e.preventDefault();
        var files;
            $.ajax({
            type: 'POST',
            url: '/user/change-settings',
            data: new FormData(this),
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,
            success: function (data) {
                if(data=='success')
                {

                    $('.js-answer-status').text('Изменения сохранены');
                }
                else
                {
                    $('.js-answer-status').text(data);
                }
            },
            error: function (error) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // franchise general-option edit
    $("form#general").on('submit',function(e){
        e.preventDefault();
        var alias=$('#alias').val();
        $.ajax({
            type: 'POST',
            url: '/lots/edit-basic/'+alias,
            data: $( this ).serialize(),
            success: function (data) {
                if(data=='success')
                {
                    $('.js-answer-status-1').text('Изменения сохранены');
                }
                else
                {
                    $('.js-answer-status-1').text(data);
                }

            },
            error: function (error) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // franchise costs-option edit
    $("form#costs").on('submit',function(e){
        e.preventDefault();
        var alias=$('#alias-1').val();
        $.ajax({
            type: 'POST',
            url: '/lots/edit-cost/'+alias,
            data: $( this ).serialize(),
            success: function (data) {
                if(data=='success')
                {
                    $('.js-answer-status-2').text('Изменения сохранены');
                }
                else
                {
                    $('.js-answer-status-2').text(data);
                }

            },
            error: function (error) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // franchise extras-option edit
    $("form#extras").on('submit',function(e){
        e.preventDefault();
        var alias=$('#alias-2').val();
        $.ajax({
            type: 'POST',
            url: '/lots/edit-extra/'+alias,
            data: $( this ).serialize(),
            success: function (data) {
                if(data=='success')
                {
                    $('.js-answer-status-3').text('Изменения сохранены');
                }
                else
                {
                    $('.js-answer-status-3').text(data);
                }

            },
            error: function (error) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // franchise contact-option edit
    $("form#contact").on('submit',function(e){
        e.preventDefault();
        var alias=$('#alias-4').val();
        $.ajax({
            type: 'POST',
            url: '/lots/edit-contact/'+alias,
            data: $( this ).serialize(),
            success: function (data) {
                if(data=='success')
                {
                    $('.js-answer-status-5').text('Изменения сохранены');
                }
                else
                {
                    $('.js-answer-status-5').text(data);
                }

            },
            error: function (error) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // franchise multimedia-option edit
    $("form#fileupload").on('submit',function(e){
        e.preventDefault();
        var alias=$('#alias-3').val();
        $.ajax({
            type: 'POST',
            url: '/lots/edit-media/'+alias,
            data: new FormData(this),
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,
            success: function (data) {
                if(data=='success')
                {
                    $('.js-answer-status-4').text('Изменения сохранены');
                }
                else
                {
                    $('.js-answer-status-4').text(data);
                }

            },
            error: function (error) {
                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // login from login page
    $("form#log").on('submit',function(e){

        e.preventDefault();
        var email=$('#email').val();
        var password=$('#password').val();

        $.ajax({
            type: 'POST',
            url: '/auth/sign-in',
            data: $( this ).serialize(),
            success: function (data) {
                if(data=='success')
                {
                    location.href='http://test8.digitalforce.ua/user/index'
                }
                else
                {
                    $('.js-answer-status').text(data);
                }

            },
            error: function (error) {

                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    // reset form, send message to email
    $("form#reset").on('submit',function(e){

        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/auth/reset',
            data: $( this ).serialize(),
            success: function (data) {
                if(data=='success')
                {
                    $('#reset').trigger("reset");
                    $('.js-answer-status').text('Вам на почту отправлена ссылка на восстановление пароля');
                }
                else
                {
                    $('.js-answer-status').text(data);
                }

            },
            error: function (error) {

                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    //reset password form
    $("form#reset-password").on('submit',function(e){

        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/auth/reset-password',
            data: $( this ).serialize(),
            success: function (data) {
                if(data=='success')
                {
                    $('#reset-password').trigger("reset");
                    $('.js-reset-status').text('Пароль успешно изменен');
                }
                else
                {
                    $('.js-reset-status').text(data);
                }

            },
            error: function (error) {

                $('form#callback').find('.bad-msg').fadeIn();
            }
        });
    });
    $("form#call").on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/forms/callback',
            data: $( this ).serialize(),
            success: function (data) {
                $('#call').trigger("reset");
                $('.js-callback-status').text(data);
            },
            error: function (data) {
                $('.js-callback-status').text(data);
            }
        });
    });
    $("form#messaging").on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/forms/messaging',
            data: $( this ).serialize(),
            success: function (data) {
                $('#messaging').trigger("reset");
                $('.js-messaging-status').text(data);
            },
            error: function (data) {
                $('.js-messaging-status').text(data);
            }
        });
    });
    $("form#cont").on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/forms/contact',
            data: $( this ).serialize(),
            success: function (data) {
                $('#cont').trigger("reset");
                $('.js-contact-status').text(data);
            },
            error: function (data) {
                $('.js-contact-status').text(data);
            }
        });
    });
})