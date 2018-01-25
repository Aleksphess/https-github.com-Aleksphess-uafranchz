$(document).ready(function(){
    
    dialog_id = $('.ad__messages').data('dialog');
       
    function checkContacts()
    {
        $.ajax({
            method: "GET",
            url: "/dialogs/check-contacts/" + dialog_id,
            cache: false,
            dataType: 'json',
            success: function(response) {
                $('.dialog_contacts').html(response.info);
            }            
        });
    }
  
    $(document).on('change', 'input[name="show_contact"]', function(){
        var is_show = $('input[name="show_contact"]:checked').val();
        
        $.ajax({
            method: "GET",
            url: "/dialogs/display-contacts/" + dialog_id + "/" + is_show,
            cache: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);
            }            
        });
    });
    
    setInterval(checkContacts, 30000);
    
});